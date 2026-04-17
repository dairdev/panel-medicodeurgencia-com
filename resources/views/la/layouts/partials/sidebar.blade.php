<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Auth::user()->context()->profileImageUrl() }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endif

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">SECCIONES</li>
            <!-- Optionally, you can add icons to the links -->
            <?php
            $menuItems = App\Models\LAMenu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
            ?>
            @foreach ($menuItems as $menu)
            @if($menu->type == "module")
                <?php
                $temp_module_obj = LAModule::get($menu->name);
                
                ?>
                @la_access($temp_module_obj->id)
                @if(isset($module->id) && $module->name == $menu->name)
             
                @if($menu->name == "Chapters")
                <?php $menu->name = 'Capitulos'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
    
                @elseif($menu->name == "Subchapters")
    
                <?php $menu->name = 'Subcapitulos'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
    
                @elseif($menu->name == "Authores")
    
                <?php $menu->name = 'Autores'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
    
                @elseif($menu->name == "Users")
    
                <?php $menu->name = 'Usuarios'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>

                @elseif($menu->name == "Employees")
    
                <?php $menu->name = 'Empresas'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>

                @elseif($menu->name == "Departments")
    
                <?php $menu->name = 'Departamentos'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>

                @elseif($menu->name == "Permissions")
    
                <?php $menu->name = 'Permisos'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
			
				@elseif($menu->name == "Uploads")    
                <?php $menu->name = 'Archivos'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
			
				@elseif($menu->name == "Specialties")
                <?php $menu->name = 'Especialidades'; ?>
                <?php echo LAHelper::print_menu($menu ,true); ?>
			
			
                
                              @else	
                              <?php echo LAHelper::print_menu($menu ,true); ?>
                              @endif 
    
                @else
    
                @if($menu->name == "Chapters")
                <?php $menu->name = 'Capitulos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
    
                @elseif($menu->name == "Subchapters")
                <?php $menu->name = 'Subcapitulos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Authores")
                <?php $menu->name = 'Autores'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Users")
                <?php $menu->name = 'Usuarios'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Employees")
                <?php $menu->name = 'Empresas'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Departments")
                <?php $menu->name = 'Departamentos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
               
			    @elseif($menu->name == "Permissions")
                <?php $menu->name = 'Permisos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
				@elseif($menu->name == "Uploads")    
                <?php $menu->name = 'Archivos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
				@elseif($menu->name == "Specialties")
                <?php $menu->name = 'Especialidades'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
			
                              @else
                              <?php echo LAHelper::print_menu($menu); ?>
                              @endif 
                  
                @endif
                        @endla_access
                    @else
                    
                    @if($menu->name == "Chapters")
                    <?php $menu->name = 'Capitulos'; ?>
                    <?php echo LAHelper::print_menu($menu); ?>
                    @elseif($menu->name == "Subchapters")
                    <?php $menu->name = 'Subcapitulos'; ?>
                    <?php echo LAHelper::print_menu($menu); ?>
                    @elseif($menu->name == "Authores")
                <?php $menu->name = 'Autores'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Users")
                <?php $menu->name = 'Usuarios'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Employees")
                <?php $menu->name = 'Empresas'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Departments")
                <?php $menu->name = 'Departamentos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
                @elseif($menu->name == "Permissions")
                <?php $menu->name = 'Permisos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
				@elseif($menu->name == "Uploads")    
                <?php $menu->name = 'Archivos'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
				@elseif($menu->name == "Specialties")
                <?php $menu->name = 'Especialidades'; ?>
                <?php echo LAHelper::print_menu($menu); ?>
			
			
			
			
			
                                  @else
                                  <?php echo LAHelper::print_menu($menu); ?>
                                  @endif 
                    @endif
                   
       
        @endforeach
            <!-- LAMenus -->
            
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
