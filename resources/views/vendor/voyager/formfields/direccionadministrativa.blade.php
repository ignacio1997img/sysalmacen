    <select class="form-control select2" name="{{ $row->field }}" data-name="{{ $row->display_name }}" @if($row->required == 1) required @endif>
        @foreach(\App\Models\Role::all() as $direccion)
        <option @if($dataTypeContent->{$row->field} == $direccion->id) selected @endif value="{{ $direccion->id }}">{{ $direccion->nombre }}</option>
        @endforeach
        {{-- <option value="">hoolal</option> --}}
    </select>


    