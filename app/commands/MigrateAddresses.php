<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateAddresses extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:migrate-addresses';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate member addresses to the new system';
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Repo\AddressRepository
     */
    private $addressRepository;

    /**
     * Create a new command instance.
     * @internal param \BB\Repo\UserRepository $userRepository
     * @internal param \BB\Repo\AddressRepository $addressRepository
     */
	public function __construct()
	{
		parent::__construct();
        $this->userRepository = App::make('\BB\Repo\UserRepository');
        $this->addressRepository = App::make('\BB\Repo\AddressRepository');
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$members = $this->userRepository->getAll();
        foreach ($members as $member) {
            $address = $this->addressRepository->getActiveUserAddress($member->id);
            if (!$address) {
                $addressFields = [
                    'line_1'    => $member->address_line_1,
                    'line_2'    => $member->address_line_2,
                    'line_3'    => $member->address_line_3,
                    'line_4'    => $member->address_line_4,
                    'postcode'  => $member->address_postcode,
                ];
                $this->addressRepository->saveUserAddress($member->id, $addressFields, true);
            }
        }
	}

}
