@extends('layouts.adminlte')

@section('subtitle', 'Fises')
@section('content_header_title', 'Fises')

@section('content_body')
    <div id="products" class="card">
        <div class="card-header">
            <div class="card-tools">
                <button class="btn btn-primary showModal" data-action="create">Nuevo fise</button>
            </div>
        </div>

        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Monto</th>
                        <th>Activo</th>
                        <th>Expiración</th>
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
                                <label for="code">Código:</label>
                                <input x-model="form.code" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.code }" id="code" name="code" maxlength="12"
                                    minlength="12" :readonly="show" />
                                <template x-if="errors?.code">
                                    <div class="invalid-feedback" x-text="errors.code[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="client_id">Cliente:</label>
                                <select x-model="form.client_id" name="client_id" id="client_id"
                                    :class="{ 'is-invalid': errors?.client_id }" :disabled="show"></select>
                                <template x-if="errors?.client_id">
                                    <div class="invalid-feedback" x-text="errors.client_id[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="amount">Monto:</label>
                                <input x-model="form.amount" type="number" class="form-control"
                                    :class="{ 'is-invalid': errors?.amount }" id="amount" name="amount" step="1"
                                    min="0" :readonly="show" />
                                <template x-if="errors?.amount">
                                    <div class="invalid-feedback" x-text="errors.amount[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="expiration_date">Fecha de expiración:</label>
                                <input x-model="form.expiration_date" type="date" class="form-control"
                                    :class="{ 'is-invalid': errors?.expiration_date }" id="expiration_date"
                                    name="expiration_date" :readonly="show" />
                                <template x-if="errors?.expiration_date">
                                    <div class="invalid-feedback" x-text="errors.expiration_date[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notas:</label>
                                <textarea x-model="form.notes" class="form-control" :class="{ 'is-invalid': errors?.notes }" :readonly="show"></textarea>
                                <template x-if="errors?.notes">
                                    <div class="invalid-feedback" x-text="errors.notes[0]"></div>
                                </template>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input x-model="form.is_active" type="checkbox" class="custom-control-input"
                                        id="is_active" :disabled="show" />
                                    <label class="custom-control-label" for="is_active"
                                        x-text="form.is_active ? 'Canjeado' : 'Disponible'"></label>
                                </div>
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
                ajax: "{{ route('panel.fises.ajax') }}",
                columns: [{
                        data: 'id',
                        searchable: false
                    },
                    {
                        data: 'code',
                    },
                    {
                        data: 'client.name',
                        orderable: false
                    },
                    {
                        data: 'client.dni',
                        orderable: false
                    },
                    {
                        data: 'amount',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return 'S/. ' + data;
                        },
                    },
                    {
                        data: 'used_at',
                        searchable: false,
                        render: function(data, type, row) {
                            return data === null ?
                                '<span class="badge badge-success">Disponible</span>' :
                                `<span class="badge badge-danger">${data}</span>`;
                        }
                    },
                    {
                        data: 'expiration_date',
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

            $('#client_id').select2({
                // minimumInputLength: 2,
                language: 'es',
                dropdownParent: $('#formModal'),
                placeholder: 'Selecciona un cliente',
                theme: 'bootstrap4',
                ajax: {
                    url: '{{ route('panel.clientes.ajax-select') }}',
                    delay: 500,
                    dataType: 'json',
                },
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
                    code: '',
                    client_id: null,
                    amount: 0,
                    expiration_date: '',
                    is_active: false,
                    notes: '',
                },
                errors: null,
                init() {
                    this.modalEl = document.getElementById('formModal');
                    this.modal = new bootstrap.Modal(this.modalEl);

                    $('#products').on('click', '.showModal', (event) => {
                        this.errors = null;
                        const actionAttr = event.currentTarget.dataset.action;

                        if (actionAttr === 'create') {
                            this.action = '{{ route('panel.fises.store') }}';
                            this.method = 'POST';
                            this.show = false;
                            this.title = 'Nuevo Fise';
                            this.form.code = '';
                            this.form.client_id = '';
                            this.form.amount = 0;
                            this.form.expiration_date = '';
                            this.form.notes = '';
                            $('#client_id').val(null).trigger('change');
                            this.modal.show();
                            return;
                        }

                        const data = $('#datatable').DataTable().row($(event.currentTarget)
                            .parents('tr')).data();

                        this.action = actionAttr === 'edit' ?
                            '{{ route('panel.fises.index') }}/' + data.id :
                            '';

                        this.method = actionAttr === 'edit' ? 'PUT' : 'GET';
                        this.show = actionAttr === 'show' ? true : false;
                        this.title = 'FISE: ' + data.code;
                        this.form.code = data.code;
                        this.form.amount = data.amount;
                        this.form.client_id = data.client.id;
                        this.form.expiration_date = data.expiration_date;
                        this.form.is_active = data.used_at ? true : false;
                        this.form.notes = data.notes;

                        const clientOption = new Option(data.client.name, data.client.id, true,
                            true);
                        $('#client_id').append(clientOption).trigger('change');


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
                                axios.delete('{{ route('panel.fises.index') }}/' +
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

                    $('#client_id').on('select2:select', (event) => {
                        this.form.client_id = parseInt(event.target.value);
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
