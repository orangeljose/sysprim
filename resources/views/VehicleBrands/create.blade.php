<x-app-layout>

    <form>
        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nombre de la Marca</label>
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ingrese el nombre de su marca" required="">
        </div>

        <a type="submit" href="{{route('vehicle_brands.index')}}" class="mr-1 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Atras</a>

        <button type="submit" id="btnNewBrand" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar</button>
    </form>
  
</x-app-layout>

<script>
    $('#btnNewBrand').on('click', function(e){
        e.preventDefault();
        let name  = $('#name').val();
        let _token = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            url: "{{route('vehicle_brands.store')}}",
            data: {
                name: name,
                _token: _token
            },
            success:function(response){
                toastr.success('Registro exitoso', 'Nuevo registro');
                setTimeout(() => {
                    window.location.href = '/api/vehicle_brands'
                }, 1000);
            },
            error: function (err) {
                if(err.status == 422){
                    $.each(err.responseJSON.errors, function (i,error) {
                        var el = $(document).find('[name="'+i+'"]');
                            el.after($('<span style="color: red;">'+error[0]+'</span>'));
                    })
                }
            }   
        });
    });
</script>




