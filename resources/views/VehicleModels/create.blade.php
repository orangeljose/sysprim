<x-app-layout>
    
    <form>

        <div >
            <ul id="tasks" class="mt-3 list-disc list-inside text-sm text-red-600">
 
            </ul>
        </div>

        <div class="mb-6">
    
            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Marca del Modelo</label>
            <select id="vehicle_brand_id" name="vehicle_brand_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected="">Elige una marca</option>
            @foreach ($vehicleBrands as $brand)
                <option value="{{$brand->id}}">{{$brand->name}}</option>
            @endforeach
            </select>


            <label for="name" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nombre del Modelo</label>
            <input required type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ingrese el nombre de del modelo" >

            <label for="year" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Año</label>
            <input required type="tel" id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ingrese el año del modelo" pattern="[0-9]{4}" >

        </div>

        <a type="submit" href="{{route('vehicle_models.index')}}" class="mr-1 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atras</a>

        <button type="submit" id="btnNewModel" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button>

    </form>
  
</x-app-layout>

<script>
    $('#btnNewModel').on('click', function(e){
        e.preventDefault();
        let vehicle_brand_id  = $('#vehicle_brand_id').val();
        let name  = $('#name').val();
        let year  = $('#year').val();
        let _token = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            url: "{{route('vehicle_models.store')}}",
            data: {
                vehicle_brand_id: vehicle_brand_id,
                name: name,
                year: year,
                _token: _token
            },
            success:function(response){
                let template = `    `;
                $('#tasks').html(template);
                toastr.success('Registro exitoso', 'Nuevo registro');
                setTimeout(() => {
                    window.location.href = '/vehicle_models'
                }, 1000);
            },
            error: function (err) {
                if(err.status == 422){
                    let template = `
                    <div class="alert flex flex-row items-center bg-red-200 p-5 rounded border-b-2 border-red-300 my-5 mb-4">
                        <div class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full mt-2">
                                <span class="text-red-500">
                                    <svg fill="currentColor"
                                        viewBox="0 0 20 20"
                                        class="h-6 w-6">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                        </div>`;

                    $.each(err.responseJSON.errors, function (i,error) {
                    template += `
                            <li class="ml-2">
                                ${error[0]}
                            </li>
                            `
                    });
                    template += `</div>`;
                    $('#tasks').html(template);
                }
            }   
        });
    });
</script>




