@extends('layouts.admin')

@section('title', 'Empleados')
@section('header', 'Gestión de Empleados')

@section('content')

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-users mr-2 text-primary"></i> Listado de Empleados
            </h3>
            <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#modal-nuevo-empleado" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo Empleado
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="bg-light">
                    <tr style="font-size: 0.85rem; text-transform: uppercase;">
                        <th class="px-3" style="width: 80px">ID</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th class="text-center" style="width: 220px">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @foreach($usuarios as $user)
                    <tr>
                        <td class="px-3 align-middle text-muted">#{{ $user->id }}</td>
                        <td class="align-middle">
                            @php
                                $imgTablar = (strpos($user->avatar, 'avatars/') === 0) 
                                             ? asset('storage/' . $user->avatar) 
                                             : asset('img/kazoku.png');
                            @endphp
                            <img src="{{ $imgTablar }}" class="img-circle shadow-sm" style="width: 35px; height: 35px; object-fit: cover; border: 1px solid #ddd;">
                        </td>
                        <td class="align-middle font-weight-bold">{{ $user->name }}</td>
                        <td class="align-middle text-primary">{{ $user->username }}</td>
                        <td class="align-middle text-muted small">{{ $user->email }}</td>
                        <td class="align-middle">
                            <span class="badge badge-info shadow-sm" style="font-weight: 500;">{{ $user->role }}</span>
                        </td>
                        <td class="text-center align-middle">
                            @if(auth()->user()->role === 'Administrador')
                                <div class="btn-group">
                                    {{-- VER TARJETÓN --}}
                                    <button type="button" class="btn btn-link text-primary p-1" data-toggle="modal" data-target="#modal-view-{{ $user->id }}" title="Ver Tarjetón">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- EDITAR ROL --}}
                                    <button type="button" class="btn btn-link text-info p-1" data-toggle="modal" data-target="#modal-edit-{{ $user->id }}" title="Editar Rol">
                                        <i class="fas fa-user-tag"></i>
                                    </button>

                                    {{-- CAMBIAR CONTRASEÑA --}}
                                    <button type="button" class="btn btn-link text-warning p-1" data-toggle="modal" data-target="#modal-password-{{ $user->id }}" title="Cambiar Contraseña">
                                        <i class="fas fa-key"></i>
                                    </button>

                                    {{-- ELIMINAR --}}
                                    <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link text-danger p-1 btn-confirm-delete" data-username="{{ $user->username }}" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>

                    {{-- MODAL TARJETÓN DE EMPLEADO (CREDENCIAL) --}}
                    <div class="modal fade" id="modal-view-{{ $user->id }}">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                                <div class="modal-body p-0">
                                    <div class="bg-primary text-center py-4">
                                        @php
                                            $urlImagen = (strpos($user->avatar, 'avatars/') === 0) 
                                                         ? asset('storage/' . $user->avatar) 
                                                         : asset('img/kazoku.png');
                                        @endphp
                                        <img src="{{ $urlImagen }}" 
                                             class="img-circle elevation-2 shadow" 
                                             style="width: 100px; height: 100px; border: 4px solid #fff; object-fit: cover;"
                                             alt="User avatar">
                                        
                                        <h5 class="mt-3 text-white font-weight-bold mb-0">{{ $user->name }}</h5>
                                        <span class="badge badge-light text-primary px-3 py-1 mt-2" style="border-radius: 50px; font-size: 0.8rem;">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                    <div class="p-4 bg-white text-center">
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1 text-uppercase tracking-wider" style="font-size: 0.7rem;">ID de Empleado</small>
                                            <h6 class="font-weight-bold">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <i class="fas fa-user-circle text-primary mr-1"></i>
                                            <small class="text-muted d-block">Usuario de Sistema</small>
                                            <span class="font-weight-bold">{{ $user->username }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-envelope text-primary mr-1"></i>
                                            <small class="text-muted d-block">Correo Registrado</small>
                                            <span class="font-weight-bold small text-truncate d-block">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-light p-3 text-center border-top d-flex justify-content-between align-items-center">
                                        <img src="{{ asset('img/kazoku.png') }}" style="height: 25px; opacity: 0.6;" alt="Logo Small">
                                        <button type="button" class="btn btn-secondary btn-xs px-3" data-dismiss="modal" style="border-radius: 50px;">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL EDITAR ROL --}}
                    <div class="modal fade" id="modal-edit-{{ $user->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 12px;">
                                <div class="modal-header bg-info py-2">
                                    <h6 class="modal-title text-white font-weight-bold">Editar Rol: {{ $user->username }}</h6>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Seleccione el nuevo Rol</label>
                                            <select name="role" class="form-control form-control-sm" required>
                                                <option value="Administrador" {{ $user->role == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                                <option value="Mesero" {{ $user->role == 'Mesero' ? 'selected' : '' }}>Mesero</option>
                                                <option value="Cajero" {{ $user->role == 'Cajero' ? 'selected' : '' }}>Cajero</option>
                                                <option value="Cocina" {{ $user->role == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 py-2">
                                        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-info btn-sm px-4">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL CAMBIAR CONTRASEÑA --}}
                    <div class="modal fade" id="modal-password-{{ $user->id }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 12px;">
                                <div class="modal-header bg-warning py-2">
                                    <h6 class="modal-title font-weight-bold">Cambiar Contraseña: {{ $user->username }}</h6>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('usuarios.password.update', $user->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Nueva Contraseña</label>
                                            <input type="password" name="password" class="form-control form-control-sm" required minlength="8" placeholder="Mínimo 8 caracteres">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Confirmar Contraseña</label>
                                            <input type="password" name="password_confirmation" class="form-control form-control-sm" required placeholder="Repita la contraseña">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 py-2">
                                        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning btn-sm px-4 font-weight-bold">Actualizar Contraseña</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL NUEVO EMPLEADO --}}
<div class="modal fade" id="modal-nuevo-empleado">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-success py-2">
                <h6 class="modal-title text-white font-weight-bold">Registrar Nuevo Empleado</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="previewAvatar" src="{{ asset('img/kazoku.png') }}" class="img-circle elevation-1" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #28a745;">
                    </div>

                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Nombre Completo</label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="row">
                        <div class="col-6 pr-1">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Usuario</label>
                                <input type="text" name="username" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-6 pl-1">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold">Rol</label>
                                <select name="role" class="form-control form-control-sm" required>
                                    <option value="Mesero">Mesero</option>
                                    <option value="Cajero">Cajero</option>
                                    <option value="Cocina">Cocina</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Foto de Perfil (Opcional)</label>
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatarFile" accept="image/*">
                            <label class="custom-file-label custom-file-label-sm" for="avatarFile">Seleccionar imagen...</label>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Contraseña Inicial</label>
                        <input type="password" name="password" class="form-control form-control-sm" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer border-0 py-2">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Previsualización de imagen al seleccionar archivo
        $('#avatarFile').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            
            // Lógica de Preview
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#previewAvatar').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        @if(session('success'))
            Swal.fire({ icon: 'success', title: '¡Éxito!', text: '{{ session("success") }}', timer: 2000, showConfirmButton: false });
        @endif

        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Revisa los campos', html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>' });
        @endif

        $('.btn-confirm-delete').on('click', function() {
            let username = $(this).data('username');
            let form = $(this).closest('form');
            Swal.fire({
                title: '¿Eliminar a ' + username + '?',
                text: "No podrá acceder al sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
</script>
@endpush