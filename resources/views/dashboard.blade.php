@extends('layouts.adminlte')

@push('css')
    {{-- Add here extra stylesheets --}}
@endpush

@section('subtitle', 'Inicio')
@section('content_header_title', 'Inicio')

@section('content_body')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Ventas del día</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53</h3>
                            <p>Ventas del mes</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-cart-plus"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $available_fises }}</h3>
                            <p>Fises disponibles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('panel.fises.index') }}" class="small-box-footer">
                            Ver más <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Deudas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">Ventas Recientes</h3>
                            <div class="card-tools">
                                {{-- <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-bars"></i>
                                </a> --}}
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($last_fises as $fise)
                                        <tr>
                                            <td>Marge Simpson</td>
                                            <td>Solgas 10kg</td>
                                            <td>S/. 50</td>
                                            <td class="text-center">
                                                <span class="badge badge-success">Cancelado</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-xs btn-primary"><i
                                                        class="far fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold">Fises Recientes</h3>
                            <div class="card-tools">
                                {{-- <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-bars"></i>
                                </a> --}}
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Fise</th>
                                        <th>Precio</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($last_fises as $fise)
                                        <tr>
                                            <td>{{ $fise->client->name }}</td>
                                            <td>{{ $fise->code }}</td>
                                            <td>S/. {{ $fise->amount }}</td>
                                            <td class="text-center">
                                                @if (!$fise->used_at)
                                                    <span class="badge badge-success">Disponible</span>
                                                @else
                                                    <span class="badge badge-danger">Canjeado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Add here extra scripts --}}
@endpush
