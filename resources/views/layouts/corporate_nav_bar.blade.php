
@if(
in_array('Newbusinessproposal-list',$userPermissions)
|| in_array('Pettycashcategory-list',$userPermissions)
|| in_array('Pettycash-list',$userPermissions)
|| in_array('Mobilebillpayment-list',$userPermissions))


<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">
  @if(in_array('company-list',$userPermissions) || in_array('Vat-list',$userPermissions) )
      <div class="dropdown">
        <a role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="companylink">
                  Company <span class="caret"></span>
       </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          @if(in_array('company-list',$userPermissions))
          <li><a class="dropdown-item" href="{{ route('Company') }}">Company</a></li>
          @endif  
          @if(in_array('Vat-list',$userPermissions))
          <li><a class="dropdown-item" href="{{ route('vat') }}">VAT</a></li>
          @endif  
        </ul>
      </div>
    @endif

    @if(in_array('Vehicletype-list',$userPermissions) || in_array('Vehicle-list',$userPermissions)
    || in_array('Vehicle-Allocate-list',$userPermissions)|| in_array('Vehicleserviceandrepair-list',$userPermissions))

      <div class="dropdown">
        <a id="vehicle_link" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  Vehicle Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">

           @if(in_array('Vehicletype-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('vehicletype')}}">Vehicle Type</a></li>@endif
           @if(in_array('Vehicle-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('vehicle')}}">Vehicle</a></li>@endif
           @if(in_array('Vehicle-Allocate-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('vehicleallocate')}}">Vehicle Allocation</a></li>@endif
           @if(in_array('Vehicleserviceandrepair-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('vehicleserviceandrepair') }}">Vehicle Service & Repair</a></li>@endif
        </ul>
      </div>
    @endif


      @if(in_array('Supplier-list',$userPermissions)
      || in_array('Issue-list',$userPermissions)
      || in_array('Porder-list',$userPermissions)
      || in_array('Grn-list',$userPermissions)
      || in_array('StoreType-list',$userPermissions)
      || in_array('StoreList-list',$userPermissions)
      || in_array('InventoryType-list',$userPermissions)
      || in_array('InventoryList-list',$userPermissions)
      || in_array('Return-list',$userPermissions)
      || in_array('ApproveReturn-list',$userPermissions)
      || in_array('Stock-list',$userPermissions))

      <div class="dropdown">
        <a id="inventorylink" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  Inventory Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown-submenu dropdown-item">
                <a class="dropdown-submenu dropdown-item submenulistpadding" tabindex="-1" href="#">Store</a>
                
                <ul class="dropdown-menu dropdownmenucolor">
                  @if(in_array('StoreType-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('storetype')}}">Store Type</a></li>@endif
                  @if(in_array('StoreList-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('storelist')}}">Store List</a></li>@endif
                </ul>
                
              </li>
              <li class="dropdown-submenu dropdown-item">
                <a class="dropdown-submenu dropdown-item submenulistpadding" tabindex="-1" href="#">Invetory</a>
                <ul class="dropdown-menu dropdownmenucolor">
                  @if(in_array('InventoryType-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('inventorytype')}}">Inventory Type</a></li>@endif
                  @if(in_array('InventoryList-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('inventorylist')}}">Inventory List</a></li>@endif
                </ul>
              </li>
              @if(in_array('Supplier-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('supplier') }}">Supplier</a></li> @endif
          <li class="dropdown-submenu dropdown-item">
            <a class="dropdown-submenu dropdown-item submenulistpadding" tabindex="-1" href="#">Purchase & GRN</a>
            <ul class="dropdown-menu dropdownmenucolor">
              @if(in_array('Porder-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('porder')}}">Purchase Order</a></li>@endif
              @if(in_array('Grn-list',$userPermissions)) <li><a class="dropdown-item" href="{{ route('grn')}}">GRN</a></li>@endif
            </ul>
          </li>
          @if(in_array('Issue-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('issue') }}">Item Request</a></li> @endif
          <li class="dropdown-submenu dropdown-item">
            <a class="dropdown-submenu dropdown-item submenulistpadding" tabindex="-1" href="#">Return</a>
            <ul class="dropdown-menu dropdownmenucolor">
              @if(in_array('Return-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('return')}}">Add Return</a></li>@endif
              @if(in_array('ApproveReturn-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('approvereturn')}}">Approve Return</a></li>@endif
            </ul>
          </li>
          @if(in_array('Stock-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('stock') }}">Stock</a></li>@endif
          <li class="divider dropdown-item"></li>
        </ul>
      </div>
      @endif

      

      @if(in_array('Newbusinessproposal-list',$userPermissions))
      <div class="dropdown">
        <a id="newbusness_link" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  New Bussiness <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          @if(in_array('Newbusinessproposal-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('newbusinessproposal') }}">New Bussiness</a></li>@endif
        </ul>
      </div>
      @endif

      <div class="dropdown">
        <a id="fixassent_link" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  Fixed Asset Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          <li><a class="dropdown-item" href="#">Fixed Asset Management</a></li>
        </ul>
      </div>

      <div class="dropdown">
        <a id="servicemanegementlink" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  Service Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          <li><a class="dropdown-item" href="#">Service Management</a></li>
        </ul>
      </div>

      @if(in_array('Mobilebillpayment-list',$userPermissions))
      <div class="dropdown">
        <a id="mobilebilllink" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  SIM Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          @if(in_array('Mobilebillpayment-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('mobilebillpayment')}}">Mobile Bill Payment</a></li>@endif
        </ul>
      </div>
      @endif

      @if( in_array('Pettycashcategory-list',$userPermissions)
      || in_array('Pettycash-list',$userPermissions))
      <div class="dropdown">
        <a id="expenseslink" role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#">
                  Expenses Management <span class="caret"></span>
              </a>
        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
          @if(in_array('Pettycashcategory-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('pettycashcategory')}}">Petty Cash Category</a></li>@endif
          @if(in_array('Pettycash-list',$userPermissions))<li><a class="dropdown-item" href="{{ route('pettycash')}}"> Petty Cash</a></li>@endif
        </ul>
      </div>
      @endif

    </div>

    @endif

