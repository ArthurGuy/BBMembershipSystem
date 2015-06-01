<?php namespace BB\Repo;

class ProfileSkillsRepository
{

    protected $skills = [
        'midi'  => [
            'name' => 'Midi',
            'icon' => 'midi.png'
        ],
        '3dprinting'  => [
            'name' => '3D Printing',
            'icon' => '3dprinting.png'
        ],
        'arduino'  => [
            'name' => 'Arduino',
            'icon' => 'arduino.png'
        ],
        'coding'  => [
            'name' => 'Coding',
            'icon' => 'coding.png'
        ],
        'electronics'  => [
            'name' => 'Electronics',
            'icon' => 'electronics.png'
        ],
        'laser-cutter'  => [
            'name' => 'Laser Cutter',
            'icon' => 'laser-cutter.png'
        ],
        'welding'  => [
            'name' => 'Welding',
            'icon' => 'welding.png'
        ],
        'wood-work'  => [
            'name' => 'Wood Working',
            'icon' => 'wood-work.png'
        ],
        'audio'  => [
            'name' => 'Audio',
            'icon' => 'audio.png'
        ],
        'cad'  => [
            'name' => 'CAD',
            'icon' => 'cad.png'
        ],
        'art-craft'  => [
            'name' => 'Arts and Crafts',
            'icon' => 'craft.png'
        ],
        'retro-computing'  => [
            'name' => 'Retro Computing',
            'icon' => 'retro-computing.png'
        ],
        'pcb-design'  => [
            'name' => 'PCB Design & Manufacture',
            'icon' => 'pcb-design.png'
        ],
        'pc-building'  => [
            'name' => 'PC Building',
            'icon' => 'pc-building.png'
        ],
        'social-media'  => [
            'name' => 'Social Media',
            'icon' => 'social-media.png'
        ],
        'networking'  => [
            'name' => 'Networking',
            'icon' => 'networking.png'
        ],
        'graphic-design'  => [
            'name' => 'Graphic Design',
            'icon' => 'graphic-design.png'
        ],
        'biology'  => [
            'name' => 'Biology',
            'icon' => 'biology.png'
        ],
        'sewing'  => [
            'name' => 'Sewing',
            'icon' => 'sewing.png'
        ],
        'printing'  => [
            'name' => 'Printing',
            'icon' => 'printing.png'
        ],
        'microcontroller'  => [
            'name' => 'Microcontrollers',
            'icon' => 'microcontroller.png'
        ],
    ];

    /**
     * Return a full array fo the various skills
     * @return array
     */
    public function getAll()
    {
        return (array)$this->skills;
    }

    public function getSelectArray()
    {
        $selectArray = [];
        foreach ($this->getAll() as $id=>$skill) {
            $selectArray[$id] = $skill['name'];
        }
        return $selectArray;
    }
} 