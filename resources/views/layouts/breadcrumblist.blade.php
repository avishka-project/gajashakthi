

<nav aria-label="breadcrumb"style="padding-top:8px;">
    <p class="navbar-brand d-none d-sm-block topbarcolor" style="font-weight: normal"> 
        @if(request()->route()->getName() == 'corporatedashboard')
            <ol class="breadcrumb custom-breadcrumb" >
              <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}" class="breadcrumb-link">Corporate</a></li>
              <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}" class="breadcrumb-link">Corporate Dashboard</a></li>
            </ol>
        @elseif(request()->route()->getName() == 'Company')

         <ol class="breadcrumb custom-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}" class="breadcrumb-link">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('Company')}}" class="breadcrumb-link">Company</a></li>
          </ol>
          @elseif(request()->route()->getName() == 'vat')

          <ol class="breadcrumb custom-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}" class="breadcrumb-link">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vat')}}" class="breadcrumb-link">VAT</a></li>
          </ol>

        @elseif(request()->route()->getName() == 'vehicletype')

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehicletype')}}">Vehicle Type</a></li>
          </ol>

        @elseif(request()->route()->getName() == 'vehicle')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehicle')}}">Vehicle</a></li>
          </ol>
        @elseif(request()->route()->getName() == 'vehicleallocate')

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehicleallocate')}}">Vehicle Allocate</a></li>
          </ol>
        @elseif(request()->route()->getName() == 'vehicleserviceandrepair')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('corporatedashboard')}}">Corporate</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehicleserviceandrepair')}}">Vehicle Service and Repair</a></li>
          </ol>

          
        @endif
    </p>
    </nav>