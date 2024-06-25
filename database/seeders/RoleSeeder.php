<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create([
            'name'=>'administrator'
        ]);

        // Roles Permissions
        Permissions::create([
            'permission' => 'role.create',
            'description' => 'Crear roles',
        ]) -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'role.index',
            'description' => 'Listar roles',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'role.update',
            'description' => 'Actualizar un rol',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'role.assign',
            'description' => 'Asignar un rol a un usuario',
        ])  -> roles() -> sync($admin);

        // User Permissions
        Permissions::create([
            'permission' => 'user.create',
            'description' => 'Crear usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'user.index',
            'description' => 'Listar usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'user.update',
            'description' => 'Actualizar un usuario',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'user.disable',
            'description' => 'Deshabilitar un usuario',
        ])  -> roles() -> sync($admin);

        // Course Permissions
        Permissions::create([
            'permission' => 'course.create',
            'description' => 'Crear cursos',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'course.index',
            'description' => 'Listar todos cursos',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'course.update',
            'description' => 'Actualizar un curso aparte de los propios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'course.disable',
            'description' => 'Deshabilitar un curso aparte de los propios',
        ])  -> roles() -> sync($admin);

        // Course Advanced Permissions
        Permissions::create([
            'permission' => 'courseAdvanced.index.enrrolled',
            'description' => 'Ver suscripciones de otros usuarios'
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.upload',
            'description' => 'ver cursos subidos por otros usuarios sin importar el estado y la privacidad',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.make.public',
            'description' => 'Cambiar privacidad de cursos a parte de los propios',

        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.public',
            'description' => 'Obtener los cursos publicos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.private',
            'description' => 'Obtener los cursos privados de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.desactive',
            'description' => 'Obtener los cursos desactivados de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.change.status.active',
            'description' => 'Cambiar el estado a activo a cursos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.search.keyword',
            'description' => 'Buscar cursos publicos y activos por medio de palabras clave',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.index.active.public',
            'description' => 'Ver cursos publicos y activas',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'courseAdvanced.make.private',
            'description' => 'Cambiar la privacidad a privado a cursos de otros usuarios',
            
        ])  -> roles() -> sync($admin);

        // Content Permissions
        Permissions::create([
            'permission' => 'content.upload',
            'description' => 'Subir contenido en cursos subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'content.update',
            'description' => 'Actualizar contenido en cursos subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'content.index.course',
            'description' => 'Consultar el contenido de cursos de otros usuarios sin importar el estado',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'content.disable',
            'description' => 'Desactivar contenido en cursos subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'content.update.order',
            'description' => 'Actualizar el orden de contenido en cursos subidos de otros usuarios',
        ])  -> roles() -> sync($admin);

        // Content Advanced Permissions
        Permissions::create([
            'permission' => 'contentAdvanced.index.public',
            'description' => 'Consultar contenido publico en cursos subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.make.public',
            'description' => 'Cambiar privacidad a publico de contenido subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.make.private',
            'description' => 'Cambiar privacidad a privacidad de contenido subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.make.active',
            'description' => 'Cambiar estado ha activo de contenido subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.index.private',
            'description' => 'Consultar contenido con privacidad privada subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.index.desactive',
            'description' => 'Consultar contenido con estado desactivado subidos de otros usuarios',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'contentAdvanced.index.public.active',
            'description' => 'Consultar contenido con estado activo subidos de otros usuarios',
        ])  -> roles() -> sync($admin);

        // Enrollments Permissions
        Permissions::create([
            'permission' => 'enrollments.create',
            'description' => 'Suscribirse a un curso',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'enrollments.delete',
            'description' => 'Desuscribirse a un curso',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'enrollments.index.search.keyword',
            'description' => 'Buscar por palabra clave entre cursos suscriscritos de otro usuario',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'enrollments.index.active.public',
            'description' => 'Buscar por entre cursos activos y publicos suscriscritos de otro usuario',
        ])  -> roles() -> sync($admin);

        // Comments Permissions
        Permissions::create([
            'permission' => 'comments.create',
            'description' => 'Crear un comentario',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'comments.update',
            'description' => 'Actualizar un comentario',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'comments.disable',
            'description' => 'Desactivar un comentario',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'comments.index.content',
            'description' => 'Ver comentarios de un curso',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'comments.index.user',
            'description' => 'Ver comentarios de un usuario en especifico',
        ])  -> roles() -> sync($admin);
        
        // Qualification Permissions
        Permissions::create([
            'permission' => 'qualification.create',
            'description' => 'Calificar un curso',
        ])  -> roles() -> sync($admin);
        Permissions::create([
            'permission' => 'qualification.update',
            'description' => 'Actualizar calificación',
        ])  -> roles() -> sync($admin);

    }
}
