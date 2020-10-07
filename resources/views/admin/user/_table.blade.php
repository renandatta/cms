<table class="table">
    <thead>
    <tr>
        <th width="50px" class="text-center">No</th>
        <th>Nama</th>
        <th>Username</th>
        <th class="text-right">Terakhir Login</th>
        @if(in_array('edit', $action))
            <th width="50px"></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($user instanceof \Illuminate\Pagination\Paginator)
        @php
            $no = isset($user) ? $user->firstItem() : 1;
        @endphp
    @else
        @php($no = 1)
    @endif
    @forelse($user as $key => $value)
        <tr>
            <td class="text-center bg-white">{{ $no++ }}</td>
            <td class="bg-white">{{ $value->nama }}</td>
            <td class="bg-white">{{ $value->email }}</td>
            <td class="text-right bg-white">{{ !empty($value->last_login) ? $value->last_login->created_at : '-' }}</td>
            @if(in_array('edit', $action))
                <td class="p-2 text-right bg-white">
                    <a href="{{ route('admin.user.info', 'id=' . $value->id) }}" class="btn btn-sm btn-secondary px-3" title="Edit details">
                        Ubah
                    </a>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="99" class="text-center bg-white">Data kosong</td>
        </tr>
    @endforelse
    </tbody>
</table>
@if(method_exists($user,'links'))
    {{ $user->links() }}
@endif
