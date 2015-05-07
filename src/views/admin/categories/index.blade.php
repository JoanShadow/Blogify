<?php
$trashed        = ($trashed) ? 1 : 0;
$currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::categories.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', array('class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'))
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.categories.index') : route('admin.categories.overview', ['trashed']) }}" title=""> {{ ($trashed) ? trans('blogify::categories.overview.links.active') : trans('blogify::categories.overview.links.trashed') }} </a>
    </p>

@section ('cotable_panel_title', ($trashed) ? trans("blogify::categories.overview.table_head.title_trashed") : trans("blogify::categories.overview.table_head.title_active"))
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
        <tr>
            <th role="name"><a href="{!! route('admin.api.sort', ['categories', 'name', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by name" class="sort"> {{ trans("blogify::categories.overview.table_head.name") }} </a></th>
            <th role="created_at"><a href="{!! route('admin.api.sort', ['categories', 'created_at', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by created at" class="sort"> {{ trans("blogify::categories.overview.table_head.created_at") }} </a></th>
            <th> {{ trans("blogify::categories.overview.table_head.actions") }} </th>
        </tr>
        </thead>
        <tbody>
        @if ( count($categories) <= 0 )
            <tr>
                <td colspan="7">
                    <em>@lang('blogify::categories.overview.no_results')</em>
                </td>
            </tr>
        @endif
        @foreach ( $categories as $category )
            <tr>
                <td>{!! $category->name !!}</td>
                <td>{!! $category->created_at !!}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', [$category->hash] ) }}"><span class="fa fa-edit fa-fw"></span></a>
                    {!! Form::open( [ 'route' => ['admin.categories.destroy', $category->hash], 'class' => $category->hash . ' form-delete' ] ) !!}
                    {!! Form::hidden('_method', 'delete') !!}
                    <a href="#" title="{{$category->name}}" class="delete" id="{{$category->hash}}"><span class="fa fa-trash-o fa-fw"></span></a>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', array('header'=>true, 'as'=>'cotable'))

{!! $categories->render() !!}

@stop