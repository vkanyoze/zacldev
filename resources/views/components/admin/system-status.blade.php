@props([
    'system' => [
        'status' => 'operational',
        'version' => '1.0.0',
        'environment' => 'production',
        'maintenance' => false,
        'lastCronRun' => null,
        'queueStatus' => 'running',
        'storage' => [
            'used' => 0,
            'total' => 0,
            'percentage' => 0,
        ],
        'memory' => [
            'used' => 0,
            'total' => 0,
            'percentage' => 0,
        ],
        'database' => [
            'size' => 0,
            'tables' => 0,
            'connection' => 'connected',
        ],
        'lastBackup' => null,
    ]
])

@php
    $statusColors = [
        'operational' => 'bg-green-100 text-green-800',
        'degraded' => 'bg-yellow-100 text-yellow-800',
        'down' => 'bg-red-100 text-red-800',
        'maintenance' => 'bg-blue-100 text-blue-800',
    ];
    
    $statusIcons = [
        'operational' => 'check-circle',
        'degraded' => 'exclamation-circle',
        'down' => 'x-circle',
        'maintenance' => 'wrench-screwdriver',
    ];
    
    $currentStatus = $system['maintenance'] ? 'maintenance' : $system['status'];
    $statusColor = $statusColors[$currentStatus] ?? $statusColors['operational'];
    $statusIcon = $statusIcons[$currentStatus] ?? 'question-mark-circle';
    
    $formatBytes = function($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    };
@endphp

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">System Status</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($statusIcon === 'check-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif($statusIcon === 'exclamation-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    @elseif($statusIcon === 'x-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif($statusIcon === 'wrench-screwdriver')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 1.24-2.085 2.04-2.599.8-.514 1.684-.877 2.66-.993.148-.017.297-.006.442.033.262.066.476.222.617.418.14.196.2.43.17.664-.053.51-.13 1.1-.234 1.67-.12.63-.28 1.2-.468 1.694-.26.7-.56 1.2-.765 1.593-.125.24-.384.39-.655.39h-.01a.75.75 0 01-.665-.39 7.95 7.95 0 01-.766-1.593c-.187-.494-.347-1.065-.467-1.693-.105-.57-.182-1.16-.235-1.67a.75.75 0 01.17-.664c.141-.196.355-.352.617-.418.145-.039.294-.05.442-.033.976.116 1.86.48 2.66.993.8.514 1.49 1.434 2.04 2.6M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    @endif
                </svg>
                {{ ucfirst($currentStatus) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- System Info -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-3">Application</h4>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Version</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $system['version'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Environment</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $system['environment'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Queue Status</dt>
                        <dd class="flex items-center">
                            @if($system['queueStatus'] === 'running')
                                <span class="flex h-2 w-2 rounded-full bg-green-400 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">Running</span>
                            @else
                                <span class="flex h-2 w-2 rounded-full bg-red-400 mr-2"></span>
                                <span class="text-sm font-medium text-gray-900">Stopped</span>
                            @endif
                        </dd>
                    </div>
                    @if($system['lastCronRun'])
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Last Cron Run</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $system['lastCronRun']->diffForHumans() }}
                            <span class="text-gray-400 ml-1">({{ $system['lastCronRun']->format('M j, Y H:i') }})</span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            
            <!-- Storage -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-3">Storage</h4>
                <dl class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <dt class="text-gray-500">Disk Usage</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $formatBytes($system['storage']['used']) }} / {{ $formatBytes($system['storage']['total']) }}
                                <span class="text-gray-500">({{ $system['storage']['percentage'] }}%)</span>
                            </dd>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $storageColor = $system['storage']['percentage'] > 90 ? 'bg-red-500' : 
                                             ($system['storage']['percentage'] > 75 ? 'bg-yellow-500' : 'bg-blue-500');
                            @endphp
                            <div class="h-2 rounded-full {{ $storageColor }}" style="width: {{ $system['storage']['percentage'] }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <dt class="text-gray-500">Memory Usage</dt>
                            <dd class="font-medium text-gray-900">
                                {{ $formatBytes($system['memory']['used'] * 1024 * 1024) }} / {{ $formatBytes($system['memory']['total'] * 1024 * 1024) }}
                                <span class="text-gray-500">({{ $system['memory']['percentage'] }}%)</span>
                            </dd>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $memoryColor = $system['memory']['percentage'] > 90 ? 'bg-red-500' : 
                                            ($system['memory']['percentage'] > 75 ? 'bg-yellow-500' : 'bg-green-500');
                            @endphp
                            <div class="h-2 rounded-full {{ $memoryColor }}" style="width: {{ $system['memory']['percentage'] }}%"></div>
                        </div>
                    </div>
                    
                    @if($system['lastBackup'])
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <dt class="text-sm text-gray-500">Last Backup</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            {{ $system['lastBackup']->diffForHumans() }}
                            <span class="text-gray-400 ml-1">({{ $system['lastBackup']->format('M j, Y H:i') }})</span>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    
    @if($system['maintenance'] && $system['maintenance_message'] ?? false)
    <div class="bg-blue-50 border-t border-blue-100 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Maintenance Mode</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>{{ $system['maintenance_message'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // System status component loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('System status component loaded');
    });
</script>
@endpush
