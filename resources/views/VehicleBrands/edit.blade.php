<x-app-layout>

    <form>

        <div >
            <ul id="tasks" class="mt-3 list-disc list-inside text-sm text-red-600">
 
            </ul>
        </div>

        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nombre de la Marca</label>
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            value="{{$brand->name}}" placeholder="ingrese el nombre de su marca" required>
            
            <span id="ErrorEdit"></span>
            <input id="Id" name="Id" type="hidden" value="{{$brand->id}}">
        </div>

        <a type="submit" href="{{route('vehicle_brands.index')}}" class="mr-1 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atras</a>

        <button type="submit" id="btnEditBrand" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Editar</button>

    </form>
  
</x-app-layout>

<script>
    $('#btnEditBrand').on('click', function(e){
        e.preventDefault();
        let name  = $('#name').val();
        let id  = $('#Id').val();
        let _token = $('input[name=_token]').val();
        let id_original = $('#Id').val();
        $.ajax({
            type: "PUT",
            url: "{{route('vehicle_brands.update', $brand->id)}}",
            data: {
                name: name,
                id: id,
                id_original: id_original,
                _token: _token
            },
            success:function(response){
                let template = `    `;
                $('#tasks').html(template);
                toastr.success('Modificación exitosa', 'Modificar registro', 3000);
                setTimeout(() => {
                    window.location.href = '/vehicle_brands'
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