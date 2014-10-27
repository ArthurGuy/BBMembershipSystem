<?php namespace BB\Html;

use Illuminate\Html\HtmlBuilder as IlluminateHtmlBuilder;

class HtmlBuilder extends IlluminateHtmlBuilder
{

    public function statusLabel($status)
    {
        if ($status == 'setting-up') {
            return '<span class="label label-warning" data-toggle="tooltip" data-placement="top" title="We are waiting for a subscription to be setup">Setting Up</span>';
        } elseif ($status == 'active') {
            return '<span class="label label-success">Active</span>';
        } elseif ($status == 'payment-warning') {
            return '<span class="label label-danger" data-toggle="tooltip" data-placement="top" title="There is something wrong with your subscription">Payment Warning</span>';
        } elseif ($status == 'leaving') {
            return '<span class="label label-default" data-toggle="tooltip" data-placement="top" title="Your leaving and will loose access when your payment expires">Leaving</span>';
        } elseif ($status == 'on-hold') {
            return '<span class="label label-default">On Hold</span>';
        } elseif ($status == 'left') {
            return '<span class="label label-default">Left</span>';
        } elseif ($status == 'honorary') {
            return '<span class="label label-default">Honorary</span>';
        }
    }

    public function spaceAccessLabel($active)
    {
        if ($active) {
            return '<label class="label label-success" data-toggle="tooltip" data-placement="top" title="You are allowed to use the space">Access to the space</label>';
        } else {
            return '<label class="label label-danger" data-toggle="tooltip" data-placement="top" title="You do not have permission to use the space">No access to the space</label>';
        }
    }

    public function keyHolderLabel($key_holder)
    {
        if ($key_holder) {
            return '<label class="label label-success" data-toggle="tooltip" data-placement="top" title="You can access the space when ever you want">Key Holder</label><br />';
        } else {
            return '<label class="label label-default" data-toggle="tooltip" data-placement="top" title="You can only use the space when a key holder is present">Key Holder: not yet</label>';
        }
    }

    public function profileSocialMediaListItem($name, $url)
    {
        if ($name && $url) {
            return '<li>'.$name.' - <a href="'.$url.'" title="'.$name.'">'.$url.'</a></li>';
        }
    }

    public function sortUsersBy($column, $body)
    {
        $direction = (\Request::get('direction') == 'asc') ? 'desc' : 'asc';
        return link_to_route('account.index', $body, ['sortBy'=>$column, 'direction'=>$direction, 'page'=>\Request::get('page'), 'showLeft'=>\Request::get('showLeft')]);
    }

    public function userPaginatorLinks($userCollection)
    {
        return $userCollection->appends(['sortBy'=>\Request::get('sortBy'), 'direction'=>\Request::get('direction'), 'showLeft'=>\Request::get('showLeft')])->links();
    }

    public function sideNavLink($name, $route, $routeParams=[])
    {
        return '<li><a href="'.route($route, $routeParams).'">'.$name.'</a></li>';
    }

}