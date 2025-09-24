<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phoneNumber;
    public $address;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $avatar;
    public $tempAvatar;
    public $editable = false;

    
    protected function rules() 
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                 Rule::unique('users')->ignore(Auth::id()),
            ],
            'phoneNumber' => 'nullable|string|max:20', 
            'address' => 'nullable|string|max:255',
        ];
    }
     
    protected $validationAttributes = [
        'phoneNumber' => 'số điện thoại',
        'tempAvatar' => 'ảnh đại diện'
    ];
    
    public function mount($editable = false)
    {
        $this->editable = $editable;
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phoneNumber = $user->phone_number ?? '';
        $this->address = $user->address ?? '';
        $this->avatar = $user->avatar;
    }
    
    public function updated($propertyName)
    {
        if ($propertyName === 'tempAvatar') {
             $this->validateOnly($propertyName, ['tempAvatar' => 'nullable|image|max:1024']); 
        } else {
             $this->validateOnly($propertyName);
        }
    }
    
    public function updateProfile()
    {
        $validatedData = $this->validate(); 

        $user = User::find(Auth::id());
        
        if ($this->tempAvatar) {
             $this->validate(['tempAvatar' => 'required|image|max:1024']); 

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Lưu avatar mới
            $path = $this->tempAvatar->store('avatars', 'public');
            $user->avatar = $path; 
            $this->avatar = $path; 
            $this->tempAvatar = null; 
        }
        
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phoneNumber']; 
        $user->address = $validatedData['address'];
        
        $user->save(); 
        
        session()->flash('message', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
    
    // Cập nhật mật khẩu
    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|different:currentPassword',
            'newPasswordConfirmation' => 'required|same:newPassword',
        ]);
        
        $user = User::find(Auth::id());
        
        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Mật khẩu hiện tại không chính xác.');
            return;
        }
        
        $user->password = Hash::make($this->newPassword);
        $user->save();
        
        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']); // Dùng reset() cho gọn
        
        session()->flash('password_message', 'Mật khẩu đã được cập nhật thành công.');
    }
    
    public function render()
    {
        return view('livewire.components.user-profile');
    }
}