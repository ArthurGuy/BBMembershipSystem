<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Equipment extends Model {

    use PresentableTrait;

    protected $presenter = 'BB\Presenters\EquipmentPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    protected $fillable = [
        'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'location', 'room', 'detail', 'key',
        'device_key', 'description', 'help_text', 'owner_role_id', 'requires_induction', 'working',
        'permaloan', 'permaloan_user_id', 'access_fee', 'photos', 'archive', 'obtained_at', 'removed_at',
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'obtained_at', 'removed_at');
    }

    /**
     * Does the equipment have activity recorded against it
     *
     * @return bool
     */
    public function hasActivity()
    {
        return !empty($this->device_key);
    }

    /**
     * Does the equipment need an induction to use it
     *
     * @return bool
     */
    public function requiresInduction()
    {
        return (bool)$this->requires_induction;
    }

    /**
     * @return bool
     */
    public function isWorking()
    {
        return (bool)$this->working;
    }

    /**
     * @return bool
     */
    public function hasPhoto()
    {
        return (bool)$this->photos;
    }

    public function isPermaloan()
    {
        return (bool)$this->permaloan;
    }

    /**
     * Generate the filename for the image, this will depend on which in the sequence it is
     *
     * @param $num
     * @return string
     */
    public function getPhotoPath($num = 1)
    {
        $filename = \App::environment() . '/equipment-images/' . md5($this->key) . '-'.$num.'.png';

        return $filename;
    }

    public function getPhotoUrl($num = 1)
    {
        return 'https://s3-eu-west-1.amazonaws.com/'.getenv('S3_BUCKET').'/'.$this->getPhotoPath($num);
    }
} 