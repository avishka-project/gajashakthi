@if(in_array('customer-list',$userPermissions)
|| in_array('branch-list',$userPermissions)
|| in_array('customercategory-list',$userPermissions)
|| in_array('subcustomer-list',$userPermissions)
|| in_array('Region-list',$userPermissions)
|| in_array('Sub-Region-list',$userPermissions)
|| in_array('CustomerRequest-list',$userPermissions))

<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">

    @if(in_array('customer-list',$userPermissions) || in_array('branch-list',$userPermissions)
    || in_array('customercategory-list',$userPermissions)|| in_array('subcustomer-list',$userPermissions)
    || in_array('Region-list',$userPermissions)|| in_array('Sub-Region-list',$userPermissions))

    <div class="dropdown">
      <a  role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="clientmaster">
                Master Data <span class="caret"></span></a>
      <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
        @if(in_array('Region-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('regions')}}">Region</a></li>
        @endif  

        @if(in_array('Sub-Region-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('subregions')}}">Sub Region</a></li>
        @endif  

        @if(in_array('customer-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('customers')}}">Client</a></li>
        @endif 

        @if(in_array('subcustomer-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('subcustomers')}}">Sub Client</a></li>
        @endif 

        @if(in_array('branch-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('branchers')}}">Client Branch</a></li>
        @endif 

        @if(in_array('customercategory-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('cuscategory')}}"> Client Category</a></li>
        @endif 

      </ul>
    </div>
  @endif

  @if(in_array('CustomerRequest-list',$userPermissions))
  <a role="button" class="btn navbtncolor"  href="{{ route('customerrequest') }}" id="authorizedcardelink">
    Authorized Cadre <span class="caret"></span> </a>
  @endif  

  <div class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
        Invoice Management <span class="caret"></span>
          </a>
    <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
      <li><a class="dropdown-item" href="#">Fixed Asset Management</a></li>
      <li><a class="dropdown-item" href="#"> Fixed Asset Management</a></li>
    </ul>
  </div>
  

</div>
@endif