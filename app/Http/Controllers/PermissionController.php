<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\Role;
use App\User;

class PermissionController extends Controller
{
    public function Permission()
    {   
    	$admin_permission = Permission::where('slug','create-posts')->first();
		$editor_permission = Permission::where('slug', 'edit-posts')->first();
		$reader_permission = Permission::where('slug', 'read-posts')->first();

		//RoleTableSeeder.php
		$admin_role = new Role();
		$admin_role->slug = 'admin';
		$admin_role->name = 'Admin';
		$admin_role->save();
		$admin_role->permissions()->attach($admin_permission);

		$editor_role = new Role();
		$editor_role->slug = 'editor';
		$editor_role->name = 'Editor';
		$editor_role->save();
		$editor_role->permissions()->attach($editor_permission);

		$reader_role = new Role();
		$reader_role->slug = 'reader';
		$reader_role->name = 'Reader';
		$reader_role->save();
		$reader_role->permissions()->attach($reader_permission);

		$admin_role = Role::where('slug','admin')->first();
		$editor_role = Role::where('slug', 'editor')->first();
		$reader_role = Role::where('slug', 'reader')->first();

		$createPosts = new Permission();
		$createPosts->slug = 'create-posts';
		$createPosts->name = 'Create Posts';
		$createPosts->save();
		$createPosts->roles()->attach($admin_role);

		$editPosts = new Permission();
		$editPosts->slug = 'edit-posts';
		$editPosts->name = 'Edit Posts';
		$editPosts->save();
		$editPosts->roles()->attach($editor_role);

		$readPosts = new Permission();
		$readPosts->slug = 'read-posts';
		$readPosts->name = 'Read Posts';
		$readPosts->save();
		$readPosts->roles()->attach($reader_role);

		$admin_role = Role::where('slug','admin')->first();
		$editor_role = Role::where('slug', 'editor')->first();
		$reader_role = Role::where('slug', 'reader')->first();

		$admin_perm = Permission::where('slug','create-posts')->first();
		$editor_perm = Permission::where('slug','edit-posts')->first();
		$reader_perm = Permission::where('slug','read-posts')->first();

		$admin = new User();
		$admin->name = 'Lalit Tiwari';
		$admin->email = 'lalit@gmail.com';
		$admin->password = bcrypt('lalit');
		$admin->save();
		$admin->roles()->attach($admin_role);
		$admin->permissions()->attach($admin_perm);

		$editor = new User();
		$editor->name = 'Hardik Tiwari';
		$editor->email = 'hardik@gmail.com';
		$editor->password = bcrypt('hardik');
		$editor->save();
		$editor->roles()->attach($editor_role);
		$editor->permissions()->attach($editor_perm);

		$reader = new User();
		$reader->name = 'Naman Tiwari';
		$reader->email = 'naman@gmail.com';
		$reader->password = bcrypt('naman');
		$reader->save();
		$reader->roles()->attach($reader_role);
		$reader->permissions()->attach($reader_perm);

		
		return redirect()->back();
    }
}
