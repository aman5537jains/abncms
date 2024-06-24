<h2>Plugins</h2>
@foreach($plugins as  $info)
<div class="card" style="width: 18rem;">
    <img class="card-img-top" src="{{$info->getScreenShot()}}" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">  {{$info->getName()}}</h5>
      <p class="card-text"> {{$info->getDescription()}}</p>
      @if(!$info->getOption('activate'))
         <a href="{{route('plugins-action',["name"=>get_class($info),"action"=>"activate"])}}" class="btn btn-primary">Activate</a>
      @endif
      @if($info->getOption('activate'))
          <a href="{{route('plugins-action',["name"=>get_class($info),"action"=>"inactivate"])}}" class="btn btn-primary">In Activate</a>
      @endif


    </div>
  </div>


@endforeach
