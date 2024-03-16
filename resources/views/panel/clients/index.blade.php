@extends('layouts.adminlte')

@push('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush

@section('subtitle', 'Clientes')
@section('content_header_title', 'Clientes')

@section('content_body')
    <div id="products" class="card">
        <div class="card-header">
            <div class="card-tools">
                <button class="btn btn-primary showModal" data-action="create">Nuevo cliente</button>
            </div>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
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
                            <h5 class="modal-title" id="exampleModalLabel" x-text="title"></h5>
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
                                <label for="dni">DNI:</label>
                                <input x-model="form.dni" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.dni }" id="dni" name="dni" maxlength="8"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.dni">
                                    <div class="invalid-feedback" x-text="errors.dni[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="address">Dirección:</label>
                                <input x-model="form.address" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.address }" id="address" name="address" step="0.5"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.address">
                                    <div class="invalid-feedback" x-text="errors.address[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="phone">Teléfono:</label>
                                <input x-model="form.phone" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.phone }" id="phone" name="phone" step="1"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.phone">
                                    <div class="invalid-feedback" x-text="errors.phone[0]"></div>
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
                ajax: "{{ route('panel.clientes.ajax') }}",
                columns: [{
                        data: 'id',
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'dni',
                        orderable: false
                    },
                    {
                        data: 'address',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'phone',
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
                    dni: '',
                    address: '',
                    phone: '',
                },
                errors: null,
                init() {
                    this.modalEl = document.getElementById('formModal');
                    this.modal = new bootstrap.Modal(this.modalEl);

                    $('#products').on('click', '.showModal', (event) => {
                        this.errors = null;
                        const actionAttr = event.currentTarget.dataset.action;

                        if (actionAttr === 'create') {
                            this.action = '{{ route('panel.clientes.store') }}';
                            this.method = 'POST';
                            this.show = false;
                            this.title = 'Nuevo Cliente';
                            this.form.name = '';
                            this.form.dni = '';
                            this.form.address = '';
                            this.form.phone = '';
                            this.modal.show();
                            return;
                        }

                        const data = $('#datatable').DataTable().row($(event.currentTarget)
                            .parents('tr')).data();

                        this.action = actionAttr === 'edit' ?
                            '{{ route('panel.clientes.index') }}/' + data.id :
                            '';

                        this.method = actionAttr === 'edit' ? 'PUT' : 'GET';
                        this.show = actionAttr === 'show' ? true : false;
                        this.form.name = this.title = data.name;
                        this.form.dni = data.dni;
                        this.form.address = data.address;
                        this.form.phone = data.phone;

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
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, bórralo',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete('{{ route('panel.clientes.index') }}/' +
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
