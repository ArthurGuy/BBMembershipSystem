<?php namespace BB\Repo;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PolicyRepository
{

    public function getByName($name)
    {
        $file = @file_get_contents(app('path.resources').'/polices/'.$name.'.md');
        if (!$file) {
            throw new NotFoundHttpException();
        }
        return $file;
    }
} 