<?php namespace BB\Entities;

use Carbon\Carbon;
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
        'device_key', 'description', 'help_text', 'managing_role_id', 'requires_induction', 'induction_category', 'working',
        'permaloan', 'permaloan_user_id', 'access_fee', 'photos', 'archive', 'obtained_at', 'removed_at', 'asset_tag_id',
        'usage_cost', 'usage_cost_per'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'obtained_at', 'removed_at');
    }

    public function role()
    {
        return $this->belongsTo('\BB\Entities\Role', 'managing_role_id');
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

    public function hasUsageCharge()
    {
        return (bool)$this->usageCost;
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
        return (bool)count($this->photos);
    }

    /**
     * @return bool
     */
    public function isPermaloan()
    {
        return (bool)$this->permaloan;
    }

    public function isManagedByGroup()
    {
        return (bool)$this->managing_role_id;
    }

    /**
     * Generate the filename for the image, this will depend on which in the sequence it is
     *
     * @param int $num
     * @return string
     */
    public function getPhotoPath($num = 0)
    {
        return $this->getPhotoBasePath() . $this->photos[$num]['path'];
    }

    /**
     * Get the base path all the equipment images live under
     *
     * @return string
     */
    public function getPhotoBasePath()
    {
        return \App::environment() . '/equipment-images/';
    }

    /**
     * Add a photo name to the photos array
     *
     * @param $fileName
     */
    public function addPhoto($fileName)
    {
        $photos = $this->photos;
        array_push($photos, ['path' => $fileName]);
        $this->photos = $photos;
        $this->save();
    }

    public function removePhoto($id)
    {
        $photos = $this->photos;
        unset($photos[$id]);
        $this->photos = array_values($photos);
        $this->save();
    }

    /**
     * Get the full url to a product image
     *
     * @param int $num
     * @return string
     */
    public function getPhotoUrl($num = 1)
    {
        return 'https://s3-eu-west-1.amazonaws.com/'.getenv('S3_BUCKET').'/'.$this->getPhotoPath($num);
    }

    public function getNumPhotos()
    {
        return count($this->photos);
    }

    public function setPhotosAttribute(array $value)
    {
        if (empty($value)) {
            $value = [];
        }
        $this->attributes['photos'] = json_encode($value);
    }

    /**
     * @return array
     */
    public function getPhotosAttribute()
    {
        if (empty($this->attributes['photos'])) {
            return [];
        }
        $photos = json_decode($this->attributes['photos'], true);
        if ($photos === null) {
            return [];
        }
        return $photos;
    }

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = strtolower($value);
    }

    public function getObtainedAtAttribute()
    {
        if ($this->attributes['obtained_at'] == '0000-00-00') {
            return null;
        }
        return new Carbon($this->attributes['obtained_at']);
    }

    public function getRemovedAtAttribute()
    {
        if ($this->attributes['removed_at'] == '0000-00-00') {
            return null;
        }
        return new Carbon($this->attributes['removed_at']);
    }

    public function getUsageCostAttribute()
    {
        return $this->attributes['usage_cost'] / 100;
    }

    public function setUsageCostAttribute($value)
    {
        $this->attributes['usage_cost'] = $value * 100;
    }


} 