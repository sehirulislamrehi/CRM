<aside class="main-sidebar elevation-4 sidebar-dark-lightblue">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard.index') }}" class="brand-link text-center">
        <span class="brand-text font-weight-light"><b>CRM</b><sub>v-1.0</sub></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar os-theme-dark">
        <!-- Sidebar Menu -->
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

            <li class="nav-item">
                <a href="{{ route('admin.dashboard.index') }}"
                   class="nav-link {{ Route::currentRouteName()=='admin.dashboard.index' ? 'active':'' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            @if( auth('super_admin')->check() )
                @foreach( \App\Models\Backend\UserModule\Module::orderBy('position','asc')->get() as $module )
                    @if( $module->route == null )
                        <li class="nav-item {{ get_active_module($module,Route::currentRouteName()) }}">
                            <a href="#" class="nav-link {{ get_sub_module($module,Route::currentRouteName()) }}">
                                <i class="nav-icon {{ $module->icon }}"></i>
                                <p class="menu-item-label">{{ $module->name }}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach( $module->sub_module->sortBy('position',false) as $sub_module )
                                    <li class="nav-item">
                                        <a href="{{ route($sub_module->route) }}"
                                           class="nav-link {{ Route::currentRouteName()==$sub_module->route ? 'active':'' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ ($sub_module->name) }} </p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route($module->route) }}"
                               @if( Route::currentRouteName()==$module->route )
                                   class="nav-link active"
                               @else
                                   class="nav-link"
                                @endif
                            >
                                <i class="menu-item-icon {{ $module->icon }}"></i>
                                <span class="menu-item-label">{{ $module->name }}</span>
                            </a>
                            <a href="{{ route($module->route) }}"
                               class="nav-link {{ Route::currentRouteName()==$module->route ? 'active':'' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ $module->name }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            @elseif( auth('web')->check() )
                @foreach( \App\Models\Backend\UserModule\Module::orderBy('position','asc')->get() as $module )
                    @if( can($module->key) )
                        @if( $module->route == null )
                            <li class="nav-item {{ get_active_module($module,Route::currentRouteName()) }}">
                                <a href="#" class="nav-link {{ get_sub_module($module,Route::currentRouteName()) }}">
                                    <i class="nav-icon {{ $module->icon }}"></i>
                                    <p class="menu-item-label">{{ $module->name }}
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach( $module->sub_module->sortBy('position',false) as $sub_module )
                                        @if( can($sub_module->key) )
                                            <li class="nav-item">
                                                <a href="{{ route($sub_module->route) }}"
                                                   class="nav-link {{ Route::currentRouteName()==$sub_module->route ? 'active':'' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ ($sub_module->name) }} </p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route($module->route) }}"
                                   @if( Route::currentRouteName()==$module->route )
                                       class="nav-link active"
                                   @else
                                       class="nav-link"
                                    @endif
                                >
                                    <i class="nav-icon {{ $module->icon }}"></i>
                                    <span class="nav-label">{{ $module->name }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
