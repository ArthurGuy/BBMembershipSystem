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
        } elseif ($status == 'suspended') {
            return '<span class="label label-default" data-toggle="tooltip" data-placement="top" title="Your payment has failed, please make a manual payment to reactivate your account.">Suspended</span>';
        }
    }


    public function memberPhoto($profileData, $userHash, $size = 250, $class = 'profilePhoto')
    {
        if ($profileData->profile_photo) {
            if (\Auth::guest() && $profileData->profile_photo_private) {
                return '<img src="' . \BB\Helpers\UserImage::anonymous() . '" width="' . $size . '" height="' . $size . '" class="' . $class . '" />';
            } elseif (( ! \Auth::guest() && ! \Auth::user()->shouldMemberSeeProtectedPhoto()) && $profileData->profile_photo_private) {
                return '<img src="' . \BB\Helpers\UserImage::anonymous() . '" width="' . $size . '" height="' . $size . '" class="' . $class . '" />';
            } else {
                return '<img src="' . \BB\Helpers\UserImage::thumbnailUrl($userHash) . '" width="' . $size . '" height="' . $size . '" class="' . $class . '" />';
            }
        } else {
            return '<img src="' . \BB\Helpers\UserImage::anonymous() . '" width="' . $size . '" height="' . $size . '" class="' . $class . '" />';
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
            return '<li>' . $name . ' - <a href="' . $url . '" title="' . $name . '">' . $url . '</a></li>';
        }
    }

    public function sortUsersBy($column, $body)
    {
        $direction = (\Request::get('direction') == 'asc') ? 'desc' : 'asc';
        return link_to_route('account.index', $body, ['sortBy'=>$column, 'direction'=>$direction, 'page'=>\Request::get('page'), 'showLeft'=>\Request::get('showLeft')]);
    }

    public function sortBy($column, $body, $route)
    {
        $direction = (\Request::get('direction') == 'asc') ? 'desc' : 'asc';
        return link_to_route($route, $body, ['sortBy'=>$column, 'direction'=>$direction, 'page'=>\Request::get('page'), 'date_filter'=>\Request::get('date_filter'), 'member_filter'=>\Request::get('member_filter'), 'reason_filter'=>\Request::get('reason_filter')]);
    }

    public function userPaginatorLinks($userCollection)
    {
        return $userCollection->appends(['sortBy'=>\Request::get('sortBy'), 'direction'=>\Request::get('direction'), 'showLeft'=>\Request::get('showLeft')])->links();
    }

    public function sortablePaginatorLinks($collection)
    {
        return $collection->appends(['sortBy'=>\Request::get('sortBy'), 'direction'=>\Request::get('direction'), 'date_filter'=>\Request::get('date_filter')])->links();
    }

    public function sideNavLink($name, $route, $routeParams = [])
    {
        return '<li><a href="' . route($route, $routeParams) . '">' . $name . '</a></li>';
    }

    public function paymentFormMethodDropdown($methods = [])
    {
        $possibleMethods = ['gocardless'=>'Direct Debit', 'stripe'=>'Credit/Debit Card', 'balance'=>'Pay using your Balance'];
        if ( ! empty($methods)) {
            foreach ($possibleMethods as $method => $methodName) {
                if ( ! in_array($method, $methods)) {
                    unset($possibleMethods[$method]);
                }
            }
        }
        return \Form::select('source', $possibleMethods, null, ['class'=>'form-control']);
    }
}