<x-app-layout>
    
    <form>
        <div class="mb-6">
    
            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Marca del Modelo</label>
            <select id="id_brand" name="id_brand" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

            @foreach ($vehicleBrands as $brand)
                <option value="{{$brand->id}}" 
                    {{$model->vehicleBrand->id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
            @endforeach
            </select>
            <input id="Id" name="Id" type="hidden" value="{{$model->id}}">


            <label for="name" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nombre del Modelo</label>
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ingrese el nombre de del modelo" value="{{$model->name}}" required>

            <label for="year" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Año</label>
            <input type="tel" id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ingrese el año del modelo" pattern="[0-9]{4}" value="{{$model->year}}" required>

        </div>

        <a type="submit" href="{{route('vehicle_models.index')}}" class="mr-1 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atras</a>

        <button type="submit" id="btnEditModel" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Editar</button>

    </form>
  
</x-app-layout>

<script>
    $('#btnEditModel').on('click', function(e){
        e.preventDefault();
        let id_brand  = $('#id_brand').val();        
        let name  = $('#name').val();
        let year  = $('#year').val();
        let _token = $('input[name=_token]').val();
        let id_original = $('#Id').val();
        $.ajax({
            type: "PUT",
            url: "{{route('vehicle_models.update', $model->id)}}",
            data: {
                vehicle_brand_id: id_brand,
                name: name,
                year: year,
                id_original: id_original,
                _token: _token
            },
            success:function(response){
                toastr.success('Modificación exitosa', 'Modificar registro', 3000);
                setTimeout(() => {
                    window.location.href = '/api/vehicle_models'
                }, 1000);                    
            },
            error: function (err) {
                if(err.status == 422){
                    $.each(err.responseJSON.errors, function (i,error) {
                        // $("#ErrorEdit").html($('<span style="color: red">'+error[0]+'</span>'))
                        var el = $(document).find('[name="'+i+'"]');
                            el.after($('<span style="color: red;">'+error[0]+'</span>'));
                    })
                }
            }     
        });
    });
</script>