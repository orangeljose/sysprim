<x-app-layout>

    <button id="btnNewVehicle" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        Agregar Vehiculo
    </button>
    <div class="flex flex-col">
        <div class="w-full">
            <div class="p-8 border-b border-gray-200 shadow">
                <table class="divide-y divide-gray-300" id="table-vehicles">
                    <thead class="bg-black">
                        <tr>
                            <th class="px-6 py-2 text-xs text-white">Id</th>
                            <th class="px-6 py-2 text-xs text-white">Marca</th>
                            <th class="px-6 py-2 text-xs text-white">Modelo</th>
                            <th class="px-6 py-2 text-xs text-white">Año</th>
                            <th class="px-6 py-2 text-xs text-white">Placa</th>
                            <th class="px-6 py-2 text-xs text-white">Color</th>
                            <th class="px-6 py-2 text-xs text-white">Fecha de Ingreso</th>
                            <th class="px-6 py-2 text-xs text-white">Editar</th>
                            <th class="px-6 py-2 text-xs text-white">Borrar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($vehicles as $vehicle)
                        <tr class="text-center whitespace-nowrap">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->id}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->vehicleBrand->name ?? '-'}}  
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->vehicleModel->name ?? '-'}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->vehicleModel->year ?? '-'}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->plate}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->color}}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$vehicle->entry_date}}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{route('vehicles.edit', $vehicle->id)}}" class="inline-block text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" id="delete" name="{{$vehicle->id}}" class="inline-block text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table-vehicles').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                        "lengthMenu": "Mostrar _MENU_ Registros",
                        "zeroRecords": "No se encontraron resultados",
                        "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "infoFiltered": "(Filtrado de un total de _MAX_ registros)",
                        "sSearch": "Buscar:",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "sProcessing": "Procesando...",
                    }
            })
        })
    </script>


    <script>
        $('#btnNewVehicle').on('click', function(e){
            $.ajax({
                type: "GET",
                url: "{{route('vehicles.create')}}",
                success: function() { 
                    window.location.href = '/vehicles/create'
                },
                error: function(xhr, ajaxOptions, thrownerror) { }
            })
        });
    </script>

    <script>

        $(document).on('click', '#delete', function(e){
            e.preventDefault();
            let id = $(this).attr('name');
            let _token = $('input[name=_token]').val();
            $.ajax({
                type: "DELETE",
                url: "/vehicles/"+id,
                data: {
                    id: id,
                    _token: _token
                },
                success:function(response){
                    toastr.success('eliminacion exitosa', 'Eliminar registro', 3000);
                    setTimeout(() => {
                        window.location.href = '/api/vehicles'
                    }, 1000);
                },
                error: function (err) {
                    if(err.status == 422){
                        $.each(err.responseJSON.errors, function (i,error) {
                            toastr.success('error en eliminacion', +error[0], 3000);                            
                        })
                    }
                }     
            });
        });

    </script>

</x-app-layout>