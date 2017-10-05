<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="{{asset('common/images/21.jpg')}}" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="{{ route('Index.Index') }}">
                                <span class="clear">
                                <span class="block m-t-xs"><strong class="font-bold">{{ @$userInfo->username }}</strong></span>
                                        <span class="text-muted text-xs block">
                                            {{ @$userInfo->agency_name }}
                                        </span>
                                </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li class=""><a class="J_menuItem" href="{{ route('Agency.AgencyDetail') }}">详细资料</a>
                        <li class=""><a class="J_menuItem" href="{{ route('user.editinfo',$userInfo->id) }}">修改密码</a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">H+
                </div>
            </li>

            @forelse(@$list as $item)
                @if($item['name'] != '首页')
                    <li>
                        <a href="#"><i class="fa {{$item['ico']}}"></i> <span class="nav-label">{{$item['name']}}</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            @forelse($item['child'] as $value)
                                @if(!in_array($value['id'],[216,217,218]))
                                <li>
                                    <a class="{{$value['ico']}}" href="{{empty($value['url'])? 'javascript:;':route($value['url'])}}">{{$value['name']}}</a>
                                </li>
                                @endif
                            @empty
                            @endforelse
                        </ul>
                    <li>
                @endif
            @empty
            @endforelse

        </ul>
    </div>
</nav>