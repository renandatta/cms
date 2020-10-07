<table class="table">
    <thead>
    <tr>
        <th width="50px" class="text-center">No</th>
        <th>Kategori</th>
        @if(in_array('tambah_sub', $action))
            <th width="140px"></th>
        @endif
        @if(in_array('edit', $action))
            <th width="50px"></th>
        @endif
        <th width="50px"></th>
    </tr>
    </thead>
    <tbody>
    @if($kategori instanceof \Illuminate\Pagination\Paginator)
        @php
            $no = isset($kategori) ? $kategori->firstItem() : 1;
        @endphp
    @else
        @php($no = 1)
    @endif
    @forelse($kategori as $key => $value)
        <tr>
            <td class="text-center bg-white">{{ $no++ }}</td>
            <td class="bg-white">{{ $value->nama }}</td>
            @if(in_array('tambah_sub', $action))
                <td class="p-2 text-center bg-white">
                    <a href="{{ route('admin.kategori.info', 'parent_kode=' . $value->kode) }}" class="btn btn-sm btn-secondary px-3" title="Tambah Sub">
                        Tambah Sub
                    </a>
                </td>
            @endif
            @if(in_array('edit', $action))
                <td class="p-2 text-center bg-white">
                    <a href="{{ route('admin.kategori.info', 'id=' . $value->id) }}" class="btn btn-sm btn-secondary px-3" title="Edit details">
                        Ubah
                    </a>
                </td>
            @endif
            <td class="p-2 text-center bg-white">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-secondary px-3" onclick="reposisi({{ $value->id }}, 'up')">up</button>
                    <button type="button" class="btn btn-sm btn-secondary px-3" onclick="reposisi({{ $value->id }}, 'down')">down</button>
                </div>
            </td>
        </tr>
        @foreach($value->children as $key2 => $value2)
            <tr>
                <td class="border-top-0"></td>
                <td class="p-0" colspan="99">
                    <table class="table mb-0">
                        <tbody>
                        <tr>
                            <td class="text-center bg-white" width="50px">{{ $key2+1 }}</td>
                            <td class="bg-white" @if(in_array('tambah_sub', $action)) colspan="2" @endif>{{ $value2->nama }}</td>
                            @if(in_array('edit', $action))
                                <td class="p-2 text-right bg-white" width="50px">
                                    <a href="{{ route('admin.kategori.info', 'id=' . $value2->id) }}" class="btn btn-sm btn-secondary px-3" title="Edit details">
                                        Ubah
                                    </a>
                                </td>
                            @endif
                            <td class="p-2 text-center bg-white" width="50px">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary px-3" onclick="reposisi({{ $value2->id }}, 'up')">up</button>
                                    <button type="button" class="btn btn-sm btn-secondary px-3" onclick="reposisi({{ $value2->id }}, 'down')">down</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    @empty
        <tr>
            <td colspan="99" class="text-center bg-white">Data kosong</td>
        </tr>
    @endforelse
    </tbody>
</table>
@if(method_exists($kategori,'links'))
    {{ $kategori->links() }}
@endif
