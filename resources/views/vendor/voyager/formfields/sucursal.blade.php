@php
    $data = \App\Models\SucursalUser::where('user_id',Auth::user()->id)->where('condicion',1)->first();
@endphp
<input @if($row->required == 1) required @endif type="hidden" class="form-control" name="{{ $row->field }}"
        placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ $data->sucursal_id }}">