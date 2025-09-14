@props([
    'activities' => [],
    'showViewAll' => true,
    'maxItems' => 5
])

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>
        
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($activities->take($maxItems) as $activity)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-medium">
                                    {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium text-gray-900">{{ $activity->user->name }}</span>
                                    {{ $activity->description }}
                                    @if($activity->subject_type && $activity->subject_id)
                                        <a href="{{ $activity->getUrl() }}" class="font-medium text-blue-600 hover:text-blue-500">
                                            {{ $activity->subject_type }}
                                        </a>
                                    @endif
                                </p>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <time datetime="{{ $activity->created_at->toIso8601String() }}">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </time>
                                    @if($activity->ip_address)
                                        <span class="mx-1">·</span>
                                        <span>{{ $activity->ip_address }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @switch($activity->log_name)
                                    @case('login')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Login
                                        </span>
                                        @break
                                    @case('logout')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Logout
                                        </span>
                                        @break
                                    @case('create')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Created
                                        </span>
                                        @break
                                    @case('update')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Updated
                                        </span>
                                        @break
                                    @case('delete')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Deleted
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($activity->log_name) }}
                                        </span>
                                @endswitch
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-center text-sm text-gray-500">
                        No recent activities found.
                    </li>
                @endforelse
            </ul>
        </div>
        
    </div>
</div>

@push('scripts')
<script>
    // Activity log component loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Activity log component loaded');
    });
</script>
@endpush
