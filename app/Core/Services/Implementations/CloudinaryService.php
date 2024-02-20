<?

namespace App\Core\Services\Implementations;
use App\Core\Services\Interfaces\ICloudinaryService;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

 class CLoudinaryService implements ICloudinaryService{
    protected $cloudinary;
    public function __construct(){
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true // Use https by default
            ]
        ]);
    }
    public function uploadImage(Request $request): string{

        $urlString = ';';
    foreach ($request->images as $image) {
        // Upload to Cloudinary
        $response = $this->cloudinary->uploadApi()->upload($image->getsecurePath());

        // Return the URL of the uploaded image

        $urlString .= $response['secure_url'] . ';';
    }
    return $urlString;
    }
    public function downloadimage(string $imagePath): void{
        //download image from cloudinary
    }
}
