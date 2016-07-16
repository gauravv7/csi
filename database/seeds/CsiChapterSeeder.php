<?php

use App\CsiChapter;
use Illuminate\Database\Seeder;

class CsiChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('csi_chapters');
		$data = [
			['csi_state_code' => 'DL', 'name' => 'Delhi'],
			['csi_state_code' => 'HR', 'name' => 'Gurgaon'],
			['csi_state_code' => 'UP', 'name' => 'Allahabad'],
			['csi_state_code' => 'UP', 'name' => 'Ghaziabad'],
			['csi_state_code' => 'UP', 'name' => 'Haridwar'],
			['csi_state_code' => 'UP', 'name' => 'Jhansi'],
			['csi_state_code' => 'UP', 'name' => 'Kanpur'],
			['csi_state_code' => 'UP', 'name' => 'Lucknow'],
			['csi_state_code' => 'UP', 'name' => 'Manakpur'],
			['csi_state_code' => 'UP', 'name' => 'Mathura'],
			['csi_state_code' => 'UP', 'name' => 'Noida'],
			['csi_state_code' => 'UP', 'name' => 'Varanasi'],
			['csi_state_code' => 'UT', 'name' => 'Dehradun'],
			['csi_state_code' => 'UT', 'name' => 'Haridwar'],
			['csi_state_code' => 'AS', 'name' => 'Guwahati'],
			['csi_state_code' => 'BR', 'name' => 'Patna'],
			['csi_state_code' => 'WB', 'name' => 'Durgapur'],
			['csi_state_code' => 'WB', 'name' => 'Kolkata'],
			['csi_state_code' => 'WB', 'name' => 'Siliguri'],
			['csi_state_code' => 'GJ', 'name' => 'Ahmedabad'],
			['csi_state_code' => 'GJ', 'name' => 'Rajkot'],
			['csi_state_code' => 'GJ', 'name' => 'Surat'],
			['csi_state_code' => 'GJ', 'name' => 'V V Nagar'],
			['csi_state_code' => 'GJ', 'name' => 'Vadodara'],
			['csi_state_code' => 'MP', 'name' => 'Bhopal'],
			['csi_state_code' => 'MP', 'name' => 'Gwalior'],
			['csi_state_code' => 'MP', 'name' => 'Indore'],
			['csi_state_code' => 'MP', 'name' => 'Jabalpur'],
			['csi_state_code' => 'MP', 'name' => 'Mhow'],
			['csi_state_code' => 'MP', 'name' => 'Ujjain'],
			['csi_state_code' => 'RJ', 'name' => 'Chittorgarh'],
			['csi_state_code' => 'RJ', 'name' => 'Jaipur'],
			['csi_state_code' => 'RJ', 'name' => 'Udaipur'],
			['csi_state_code' => 'CT', 'name' => 'Bhilai'],
			['csi_state_code' => 'CT', 'name' => 'Raipur'],
			['csi_state_code' => 'JH', 'name' => 'Bokaro'],
			['csi_state_code' => 'JH', 'name' => 'Dhanbad'],
			['csi_state_code' => 'JH', 'name' => 'Jamshedpur'],
			['csi_state_code' => 'JH', 'name' => 'Ranchi'],
			['csi_state_code' => 'OR', 'name' => 'Balasore'],
			['csi_state_code' => 'OR', 'name' => 'Bhubaneswar'],
			['csi_state_code' => 'OR', 'name' => 'Cuttack'],
			['csi_state_code' => 'OR', 'name' => 'Rourkela'],
			['csi_state_code' => 'AP', 'name' => 'Hyderabad'],
			['csi_state_code' => 'AP', 'name' => 'K L C E Koneru'],
			['csi_state_code' => 'AP', 'name' => 'New Guntur'],
			['csi_state_code' => 'AP', 'name' => 'Ongole'],
			['csi_state_code' => 'AP', 'name' => 'Vijayawada'],
			['csi_state_code' => 'AP', 'name' => 'Visakhapatnam'],
			['csi_state_code' => 'KA', 'name' => 'Bangalore'],
			['csi_state_code' => 'KA', 'name' => 'Mysore'],
			['csi_state_code' => 'GA', 'name' => 'Goa'],
			['csi_state_code' => 'MH', 'name' => 'Aurangabad'],
			['csi_state_code' => 'MH', 'name' => 'Mumbai'],
			['csi_state_code' => 'MH', 'name' => 'Nagpur'],
			['csi_state_code' => 'MH', 'name' => 'Nashik'],
			['csi_state_code' => 'MH', 'name' => 'Pune'],
			['csi_state_code' => 'MH', 'name' => 'Solapur'],
			['csi_state_code' => 'KL', 'name' => 'Kozhikode'],
			['csi_state_code' => 'KL', 'name' => 'Kochi'],
			['csi_state_code' => 'KL', 'name' => 'Trivandrum'],
			['csi_state_code' => 'PY', 'name' => 'Puducherry'],
			['csi_state_code' => 'TN', 'name' => 'A U Annamalainagar'],
			['csi_state_code' => 'TN', 'name' => 'Chennai'],
			['csi_state_code' => 'TN', 'name' => 'Coimbatore'],
			['csi_state_code' => 'TN', 'name' => 'Hosur'],
			['csi_state_code' => 'TN', 'name' => 'Kanyakumari'],
			['csi_state_code' => 'TN', 'name' => 'Karaikudi'],
			['csi_state_code' => 'TN', 'name' => 'Salem'],
			['csi_state_code' => 'TN', 'name' => 'Thanjavur'],
			['csi_state_code' => 'TN', 'name' => 'Sivakasi'],
			['csi_state_code' => 'TN', 'name' => 'Tiruchirapalli'],
			['csi_state_code' => 'TN', 'name' => 'Vellore']
        ];

        foreach ($data as $value) {
	        CsiChapter::create($value);
        }
    }
}