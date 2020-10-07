<table class="table">
    <thead>
    <tr>
        <th width="50px" class="text-center">No</th>
        <th width="200px">Nama</th>
        <th>Konten</th>
        @if(in_array('edit', $action))
            <th width="50px"></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($konten instanceof \Illuminate\Pagination\Paginator)
        @php
            $no = isset($konten) ? $konten->firstItem() : 1;
        @endphp
    @else
        @php($no = 1)
    @endif
    @forelse($konten as $key => $value)
        <tr>
            <td class="text-center bg-white">{{ $no++ }}</td>
            <td class="bg-white">{{ $value->nama }}</td>
            <td class="bg-white" style="white-space: normal;word-wrap: break-word;">{!! $value->konten !!}</td>
            @if(in_array('edit', $action))
                <td class="p-2 text-right bg-white">
                    <a href="{{ route('admin.konten.info', 'id=' . $value->id) }}" class="btn btn-sm btn-secondary px-3" title="Edit details">
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
@if(method_exists($konten,'links'))
    {{ $konten->links() }}
@endif
