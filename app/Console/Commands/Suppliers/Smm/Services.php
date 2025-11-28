<?php

namespace App\Console\Commands\Suppliers\Smm;

use App\Helpers\DBHelper;
use App\Models\ServerService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class Services extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smm:internal:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Task SMM';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $smm = new \App\Http\Controllers\Api\Suppliers\SmmController();
            $servicesCheck = $smm->getServices();
            $updateSupplierIDs = [];
            if ($servicesCheck['status'] && !empty($servicesCheck['data'])) {
                $serverServices = ServerService::with([
                    'serverSupplier:id,server_id,service,title,description,status_off,update_minmax'
                ])->orderBy('id')->get();
                $servers = [];
                foreach ($serverServices as $i) {
                    $servers[$i->supplier_id . "-" . $i->supplier_code] = $i;
                }
                $supplierPercents = $smm->suppliers->pluck('price_percent', 'id')->toArray();
                $newServers = [];
                $newServersSupplier = [];
                $updateServers = [];
                $updateServerSuppliers = [];

                foreach ($servicesCheck['data'] as $i) {
                    $api = $i['id'] . '-' . $i['server_code'];
                    $updateSupplierIDs[$i['id']] = true;
                    $percent = $supplierPercents[$i['id']] ?? 0;
                    $price = round($i['cost'] * (1 + $percent / 100), 2);

                    if (!isset($servers[$api])) {
                        $newServers[] = [
                            'price' => $price,
                            'min' => $i['min'],
                            'max' => $i['max'],
                            'title' => $i['title'],
                            'description' => $i['description'],
                            'supplier_id' => $i['id'],
                            'supplier_code' => $i['server_code'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $newServersSupplier[$api] = [
                            "service" => $i['service'],
                            "title" => $i['title'],
                            "description" => $i['description'],
                            'cost' => $i['cost'],
                            "status_off" => "title",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        continue;
                    }
                    $serverInfo = $servers[$api];
                    $serverSupplierInfo = $serverInfo->serverSupplier;
                    $updateServer = [
                        'id' => $serverInfo->id,
                        'price' => $price,
                        'min' => $serverInfo->min,
                        'max' => $serverInfo->max,
                        'title' => $serverInfo->title,
                        'description' => $serverInfo->description,
                        'status' => $serverInfo->status,
                        'updated_at' => now(),
                    ];
                    $updateServerSupplier = [
                        'id' => $serverSupplierInfo->id,
                        'service' => $i['service'],
                        'title' => $i['title'],
                        'description' => $i['description'],
                        'cost' => $i['cost'],
                        'updated_at' => now(),
                    ];

                    $changedTitle = $serverSupplierInfo->title !== $i['title'];
                    $changedDesc = $serverSupplierInfo->description !== $i['description'];

                    if (($serverSupplierInfo->status_off === 'title' && $changedTitle) || ($serverSupplierInfo->status_off === 'desc' && $changedDesc) || ($serverSupplierInfo->status_off === 'all' && ($changedTitle || $changedDesc))) {
                        $updateServer['status'] = false;
                    }

                    if ($serverSupplierInfo->update_minmax) {
                        $updateServer['min'] = $i['min'];
                        $updateServer['max'] = $i['max'];
                    }

                    if (empty($serverInfo->server) || empty($serverInfo->service_id)) {
                        $updateServer['title'] = $i['title'];
                        $updateServer['description'] = $i['description'];
                    }
                    $updateServers[] = $updateServer;
                    $updateServerSuppliers[] = $updateServerSupplier;

                }
                if (!empty($newServers)) {
                    DBHelper::multiInsert("server_services", $newServers);
                }
                $supplierKeys = array_map(function ($server) {
                    return [
                        'supplier_id' => $server['supplier_id'],
                        'supplier_code' => $server['supplier_code'],
                    ];
                }, $newServers);
                if (empty($supplierKeys)) {
                    $insertedNewServers = [];
                } else {
                    $insertedNewServers = ServerService::where(function ($query) use ($supplierKeys) {
                        foreach ($supplierKeys as $keyPair) {
                            $query->orWhere(function ($q) use ($keyPair) {
                                $q->where('supplier_id', $keyPair['supplier_id'])->where('supplier_code', $keyPair['supplier_code']);
                            });
                        }
                    })->get();
                }
                $newServersSuppliers = [];
                if (!empty($insertedNewServers)) {
                    foreach ($insertedNewServers as $s) {
                        $newServersSuppliers[] = array_merge(['server_id' => $s->id], $newServersSupplier[$s->supplier_id . "-" . $s->supplier_code]);
                    }
                }
                if (!empty($newServersSuppliers)) {
                    DBHelper::multiInsert("server_suppliers", $newServersSuppliers);
                }
                if (!empty($updateServers)) {
                    DBHelper::bulkUpdateJoin("server_services", $updateServers, 'id');
                }
                if (!empty($updateServerSuppliers)) {
                    DBHelper::bulkUpdateJoin("server_suppliers", $updateServerSuppliers, "id");
                }
                if (!empty($updateSupplierIDs)) {
                    ServerService::where('updated_at', '<', Carbon::now()->subHours(6))
                        ->whereIn('supplier_id', array_keys($updateSupplierIDs))
                        ->delete();
                }
            } else {
                $this->info($servicesCheck['message']);
                return self::FAILURE;
            }
            return self::SUCCESS;
        } catch (\Throwable $e) {
            \Log::error("❌ Lỗi CRON: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->error('Lỗi: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
