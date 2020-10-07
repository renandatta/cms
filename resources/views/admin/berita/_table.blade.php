<table class="table">
    <thead>
    <tr>
        <th width="50px" class="text-center">No</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Tags</th>
        <th class="text-right">Tanggal</th>
        @if(in_array('edit', $action))
            <th width="50px"></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($berita instanceof \Illuminate\Pagination\Paginator)
        @php
            $no = isset($berita) ? $berita->firstItem() : 1;
        @endphp
    @else
        @php($no = 1)
    @endif
    @forelse($berita as $key => $value)
        <tr>
            <td class="text-center bg-white">{{ $no++ }}</td>
            <td class="bg-white">{{ $value->nama }}</td>
            <td class="bg-white">{{ $value->kategori->nama }}</td>
            <td class="bg-white">{{ $value->tags }}</td>
            <td class="bg-white text-right">{{ format_date($value->tanggal) }}</td>
            @if(in_array('edit', $action))
                <td class="p-2 text-right bg-white">
                    <a href="{{ route('admin.berita.info', 'id=' . $value->id) }}" class="btn btn-sm btn-secondary px-3" title="Edit details">
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
@if(method_exists($berita,'links'))
    {{ $berita->links() }}
@endif
