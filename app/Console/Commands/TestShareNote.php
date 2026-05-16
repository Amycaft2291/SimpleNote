<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Contracts\Repositories\INoteRepository;
use App\Models\Note;
use App\Helpers\AuthHelper;
use App\Mail\ShareNoteMail;
use Illuminate\Support\Facades\Mail;

class TestShareNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:share-note {noteId : Note id} {email : Receiver email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $noteId = (int) $this->argument('noteId');
        $email = $this->argument('email');

        // 1. Tìm note để có biến Note $note
        // $note = app(INoteRepository::class)->findById();
        $note = Note::query()->find($noteId);

        // 2. check Note xem có không
        if($note == null){
            $this->error('Note not found.');
            return self::FAILURE;
        }
        
        // 3. dựng $noteUrl và $senderName 
        // gợi ý $noteUrl có thể gọi route('ten_route_show')
        // senderName có thể gọi từ user đăng nhập hiện tại trỏ tới name
        // $noteUrl = route('notes.show', $note->getKey());
        $link = "https:www.facebook.com/?locale=vi_VN";
        // $senderName = AuthHelper::getUser()->display_name;
        $senderName ="Noc";

        // 4. Khởi tạo mail
        $noteMail = new ShareNoteMail($note, $senderName, $link);

        // 5. Send email bằng cách gọi Mail::to(email_cần_gửi_tới)->send(mail_đã_khởi_tạo)
        Mail::to($email)->send($noteMail);

        $this->info('Share note mail sent successfully.');

        return self::SUCCESS;
    }
}
