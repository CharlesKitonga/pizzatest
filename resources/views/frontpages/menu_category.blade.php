<!-- Menu Category / Burgers -->
@foreach($allDetails as $category)
    @if(count($category) > 0 && $category[0]->id)
        @if(count($category[0]->products) > 0)
            <div id="menuBurgersContent<?php echo($category[0]->id);?>"
                 class="menu-category-content collapse show">
                <!-- Menu Item -->
                <div class="row menu-row">
                    @foreach($category[0]->products as $item)
                        <div class="col-6 ">
                            @if($item->id)
                                <div class="card menu-card">
                                    <div class="card-body">
                                        <div class="row justify-content-between">
                                            <div class="col-7">
                                                <h6 class="mb-0"><b>{{$item->product_name}}</b></h6>
                                                <span
                                                    class="text-muted text-sm">{{$item->description}}</span>
                                                <br>
                                                <span class="text-md">
                                                    <span class="text-muted">
                                                        {{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }}
                                                    </span>
                                                    <b>
                                                        Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}
                                                    </b>
                                                </span>
                                                <br>
                                                <br>
                                                <button type="button" class="btn btn-dark btn-xs"
                                                        data-target="#productModal{{$item->id}}"
                                                        data-toggle="modal"><span>Place order</span>
                                                </button>
                                            </div>
                                            <div class="col-5">
                                                @if(!empty($item->photo))
                                                    <img src="{{asset('images/products/'.$item->photo) }}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <br>
                            <!-- Modal / Product -->
                            <div class="modal fade" id="productModal{{$item->id}}" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-lg dark bg-dark">
                                            <div class="bg-image"><img src="{{asset('assets/img/photos/modal-add.jpg')}}" alt=""></div>
                                            <h4 class="modal-title">Specify your dish</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                        </div>
                                        <div class="modal-product-details">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <h6 class="mb-0">{{$item->product_name}}</h6>
                                                    <span class="text-muted">{{$item->description}}</span>
                                                </div>
                                                <span id="getPrice{{$item->id}}" class="text-md"> {{count($item->attributes) > 0 && $item->attributes[0]->size ? 'from' : '' }} Ksh. {{ count($item->attributes) > 0 && $item->attributes[0]->size ? $item->attributes[0]->size ? $item->attributes[0]->price ? $item->attributes[0]->price : 0 : 0 : $item->price}}</span>
                                            </div>
                                        <div class="modal-body panel-details-container">
                                        <form name="addtocartForm" id="addtocartForm{{ $item->id }}" action="{{url('add-to-cart/'.$item->id)}}" method="post">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <input type="hidden" name="product_name" value="{{ $item->product_name }}">
                                            <input type="hidden" name="price" id="price{{ $item->id }}" value="{{ $item->price }}">
                                            <input type="hidden" name="size" value="">

                                            {{-- {{ dd($item)}} --}}

                                            <!-- Panel Details / Additions -->
                                                <div class="panel-details">
                                                    @if(count($item->attributes) > 0 && $item->attributes[0]->size)
                                                        <div class="row mb-5">
                                                            <div class="form-group col-sm-12">
                                                                {{-- <label>Delivery time:</label> --}}
                                                                <div class="select-container">
                                                                <select id="selSize{{ $item->id }}" name="size" class="form-control selSize" required>
                                                                    <option value="">Select Size</option>
                                                                    @foreach($item->attributes as $getSizes)
                                                                        @if($getSizes->size)
                                                                            <option value="{{$getSizes->id}}">{{ $getSizes->size }} {{ $getSizes->price ? '(Ksh. ' . $getSizes->price . ')' : '' }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <br>
                                                <div class="col-4">
                                                    <input name="quantity" style=" margin-left: -10px; width: 90px; height:30px;" type="number" value="1" class="name" min="1" max="10"/>
                                                </div><br>
                                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-block btn-lg"><span> Confirm</span></button>
                                        </form>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    @endif
@endforeach
