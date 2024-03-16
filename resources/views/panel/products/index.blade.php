@extends('layouts.adminlte')

@section('subtitle', 'Productos')
@section('content_header_title', 'Productos')

@section('content_body')
    <div id="products" class="card">
        <div class="card-header">
            {{-- <h3 class="card-title">Listado de productos</h3> --}}
            <div class="card-tools">
                <button class="btn btn-primary showModal" data-action="create">Nuevo producto</button>
            </div>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div x-data="modals">
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bold" id="exampleModalLabel" x-text="title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input x-model="form.name" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.name }" id="name" name="name"
                                    :readonly="show" />
                                <template x-if="errors?.name">
                                    <div class="invalid-feedback" x-text="errors.name[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <textarea x-model="form.description" class="form-control" :class="{ 'is-invalid': errors?.description }"
                                    id="description" name="description" :readonly="show"></textarea>
                                <template x-if="errors?.description">
                                    <div class="invalid-feedback" x-text="errors.description[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="price">Precio:</label>
                                <input x-model="form.price" type="number" class="form-control"
                                    :class="{ 'is-invalid': errors?.price }" id="price" name="price" step="0.5"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.price">
                                    <div class="invalid-feedback" x-text="errors.price[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock:</label>
                                <input x-model="form.stock" type="number" class="form-control"
                                    :class="{ 'is-invalid': errors?.stock }" id="stock" name="stock" step="1"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.stock">
                                    <div class="invalid-feedback" x-text="errors.stock[0]"></div>
                                </template>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <template x-if="!show">
                                <button @click="sendForm" type="button" id="submitBtn"
                                    class="btn btn-primary">Guardar</button>
                            </template>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                ajax: "{{ route('panel.productos.ajax') }}",
                columns: [{
                        data: 'id',
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description',
                        orderable: false
                    },
                    {
                        data: 'price',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'stock',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: null,
                        name: 'Acciones',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<div class="d-flex">
                                <button class="btn btn-xs btn-info mr-1 showModal" data-action="show"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-xs btn-warning mr-1 showModal" data-action="edit"><i class="fa fa-edit text-white"></i></button>
                                <button class="btn btn-xs btn-danger deleteModal"><i class="fas fa-trash-alt text-white"></i></button>
                            </div>`;
                        }
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modals', () => ({
                modalEl: null,
                modal: null,
                show: false,
                action: '',
                method: 'POST',
                title: '',
                form: {
                    name: '',
                    description: '',
                    price: 0,
                    stock: 0,
                },
                errors: null,
                init() {
                    this.modalEl = document.getElementById('formModal');
                    this.modal = new bootstrap.Modal(this.modalEl);

                    $('#products').on('click', '.showModal', (event) => {
                        this.errors = null;
                        const actionAttr = event.currentTarget.dataset.action;

                        if (actionAttr === 'create') {
                            this.action = '{{ route('panel.productos.store') }}';
                            this.method = 'POST';
                            this.show = false;
                            this.title = 'Nuevo Producto';
                            this.form.name = '';
                            this.form.description = '';
                            this.form.price = 0;
                            this.form.stock = 0;
                            this.modal.show();
                            return;
                        }

                        const data = $('#datatable').DataTable().row($(event.currentTarget)
                            .parents('tr')).data();

                        this.action = actionAttr === 'edit' ?
                            '{{ route('panel.productos.index') }}/' + data.id :
                            '';

                        this.method = actionAttr === 'edit' ? 'PUT' : 'GET';
                        this.show = actionAttr === 'show' ? true : false;
                        this.form.name = this.title = data.name;
                        this.form.description = data.description;
                        this.form.price = data.price;
                        this.form.stock = data.stock;

                        this.modal.show();
                    });

                    $('#products').on('click', '.deleteModal', (event) => {
                        this.errors = null;
                        const data = $('#datatable').DataTable().row($(event.currentTarget)
                            .parents('tr')).data();

                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            cancelButtonColor: '#3085d6',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Sí, bórralo',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete('{{ route('panel.productos.index') }}/' +
                                        data.id)
                                    .then(response => {
                                        Swal.fire('Eliminado', response.data
                                            .message, 'success');

                                        $('#datatable').DataTable().ajax.reload(
                                            null, false);
                                    });
                            }
                        });
                    });
                },
                sendForm() {
                    this.errors = null;

                    axios({
                        method: this.method,
                        url: this.action,
                        data: {
                            ...this.form
                        },
                    }).then(response => {
                        Swal.fire('Éxito', response.data.message, 'success');

                        $('#datatable').DataTable().ajax.reload(null, false);

                        this.modal.hide();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            }))
        })
    </script>
@endpush
