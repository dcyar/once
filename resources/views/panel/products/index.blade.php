@extends('layouts.adminlte')

@push('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush

@section('subtitle', 'Productos')
@section('content_header_title', 'Productos')

@section('content_body')
    <div class="card">
        <div class="card-header">
            {{-- <h3 class="card-title">Listado de productos</h3> --}}
            <div class="card-tools">
                <a href="" class="btn btn-primary">Nuevo producto</a>
            </div>
        </div>

        <div class="card-body">
            <table id="products" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                {{-- <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">Ver</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Editar</a> --}}
                                <form action="{{ route('panel.productos.destroy', $product->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $product->id }}">Eliminar</button>
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Eliminar producto</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    ¿Estás seguro de que deseas eliminar este producto?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#products').DataTable();
        });

        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
            Alpine.data('modal', () => ({
                open: false,
                toggle() {
                    this.open = !this.open
                }
            }))
        })
    </script>
@endpush
