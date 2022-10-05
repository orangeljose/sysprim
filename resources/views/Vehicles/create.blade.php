<x-app-layout>
    
    <form>

        <div >
            <ul id="tasks" class="mt-3 list-disc list-inside text-sm text-red-600">
 
            </ul>
        </div>

        <div class="mb-6">

            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Marca</label>
            <select id="id_brand" name="id_brand" autofocus class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected="">Elige una marca</option>
            @foreach ($vehicleBrands as $brand)
                <option value="{{$brand->id}}">{{$brand->name}}</option>
            @endforeach
            </select>
    
            <label for="model" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-400">Modelo</label>
            <select id="vehicle_model_id" name="vehicle_model_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected="">Elige un modelo</option>
            @foreach ($vehicleModels as $model)
                <option value="{{$model->id}}">{{$model->name}} - {{$model->year}}</option>
            @endforeach
            </select>


            <label for="plate" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Placa</label>
            <input type="text" id="plate" name="plate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ingrese la placa del vehiculo" required>

            <label for="color" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Color</label>
            <input type="text" id="color" name="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ingrese el color del vehiculo" required>

            
            <div class="relative">
                <label for="datepicker" class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Fecha de Ingreso</label>
                
                <div class="mt-6 flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                </div>
                <input id="entry_date" name="entry_date" datepicker="" datepicker-format="yyyy-mm-dd" datepicker-autohide="" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 datepicker-input" placeholder="Seleccione una fecha">
            </div>
  

        </div>

        <a type="submit" href="{{route('vehicles.index')}}" class="mr-1 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atras</a>

        <button type="submit" id="btnNewVehicle" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button>

    </form>
  
</x-app-layout>

<script>
    $('#btnNewVehicle').on('click', function(e){
        e.preventDefault();
        let vehicle_model_id = $('#vehicle_model_id').val();        
        let name             = $('#name').val();
        let plate            = $('#plate').val();
        let color            = $('#color').val();
        let entry_date       = $('#entry_date').val();
        let _token           = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            url: "{{route('vehicles.store')}}",
            data: {
                vehicle_model_id: vehicle_model_id,
                name            : name,
                plate           : plate,
                color           : color,
                entry_date      : entry_date,
                _token          : _token
            },
            success:function(response){
                let template = `    `;
                $('#tasks').html(template);
                toastr.success('Registro exitoso', 'Nuevo registro');
                setTimeout(() => {
                    window.location.href = '/vehicles'
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

<script>
    $('#id_brand').on('change', function(e){
        var id = e.target.value;
        $.get('/api/vehicle_models/list/' + id,function(data) {
            $('#vehicle_model_id').empty();
            $('#vehicle_model_id').append('<option value="" disable="true" selected="true">Seleccione un modelo</option>');
            $.each(data, function(fetch, regenciesObj){
                console.log(data);
                $('#vehicle_model_id').append('<option value="'+ regenciesObj.id +'">'+ regenciesObj.name + " - " + regenciesObj.year + '</option>');
            });
        });
    });
</script>




