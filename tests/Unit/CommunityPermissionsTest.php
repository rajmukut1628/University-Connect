<?php

use App\Models\Donation;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\Mentorship;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Services\FeedInterestService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

test('students and alumni cannot alter university-managed identity through crafted requests', function (string $role, string $field) {
    $user = User::create(['name'=>'Verified Name','email'=>$role.'@identity.example.com','role'=>$role,'official_id'=>'OFF100','student_id'=>'ST100','alumni_id'=>'AL100','is_active'=>true,'is_blocked'=>false]);
    $before = $user->fresh()->getAttributes();
    $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertDontSee('name="name"',false)->assertSee('can only be changed by an administrator');
    $this->patch(route('profile.update'),[$field=>'Tampered Identity','phone'=>'01799999999'])->assertSessionHasErrors($field);
    expect($user->fresh()->getAttributes())->toEqual($before);
})->with(['student','alumni'])->with(['name','email','official_id','student_id','alumni_id']);

test('students and alumni can save permitted fields without submitting their name', function (string $role) {
    $user = User::create(['name'=>'Original Name','email'=>$role.'@editable.example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $profile = $this->actingAs($user)->get(route('profile.edit'))->assertOk();
    expect(strip_tags($profile->getContent()))->not->toContain('name) }}');
    $this->actingAs($user)->patch(route('profile.update'),['phone'=>'01712345678','bio'=>'Updated biography','skills'=>'Programming'])->assertSessionHasNoErrors()->assertRedirect();
    expect($user->fresh()->name)->toBe('Original Name')->and($user->fresh()->phone)->toBe('01712345678')->and($user->fresh()->bio)->toBe('Updated biography');
})->with(['student','alumni']);

test('admin can still edit student and alumni identity through user management', function (string $role) {
    $user = $role === 'student' ? $this->student : $this->mentor;
    $admin = User::create(['name'=>'Identity Admin','email'=>'identity-admin@example.com','role'=>'admin','is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($admin)->patch(route('admin.users.update',$user),['name'=>'Admin Updated Name','email'=>'admin-updated@example.com','official_id'=>'UPDATED100','role'=>$role,'is_active'=>true])->assertSessionHasNoErrors()->assertRedirect();
    expect($user->fresh()->name)->toBe('Admin Updated Name')->and($user->fresh()->email)->toBe('admin-updated@example.com')->and($user->fresh()->official_id)->toBe('UPDATED100');
    $this->actingAs($user->fresh())->get(route('profile.edit'))->assertOk()->assertSee('UPDATED100');
})->with(['student','alumni']);

test('admin and super admin retain their existing self-service name editing', function (string $role) {
    $user = User::create(['name'=>'Admin Name','email'=>$role.'@own-profile.example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertSee('name="name"',false);
    $this->patch(route('profile.update'),['name'=>'Updated Admin Name'])->assertSessionHasNoErrors()->assertRedirect();
    expect($user->fresh()->name)->toBe('Updated Admin Name');
})->with(['admin','super_admin']);

test('login password starts hidden and offers an accessible non-submit visibility toggle', function () {
    $this->get(route('login'))->assertOk()->assertSee('type="password"',false)
        ->assertSee('showPassword: false',false)->assertSee('showPassword = !showPassword',false)
        ->assertSee('aria-controls="password"',false)->assertSee('aria-label="Show password"',false)
        ->assertSee('type="button"',false)->assertSee("showPassword ? 'text' : 'password'",false);
});

test('commonly typed addresses explain the correct destination without opening registration', function () {
    foreach (array_keys(\App\Http\Controllers\PageInformationController::TOPICS) as $path) {
        $this->get('/'.$path)->assertOk()->assertSee('University Connect')->assertSee('Login')->assertSee(route('home'));
    }
    $this->get('/registration')->assertSee('Self-registration is unavailable')->assertSee('university-verified records')->assertDontSee('name="password"',false);
    $this->post('/registration', ['name'=>'Unapproved Signup'])->assertStatus(405);
    $this->get('/logout')->assertStatus(405)->assertSee('Open this action from its page');
});

test('information pages offer only the signed in users permitted destinations', function (string $role) {
    $user = User::create(['name'=>'Info Tester','email'=>$role.'@info.example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($user)->get('/registration')->assertOk()->assertSee('My Dashboard')->assertDontSee('>Login</a>',false);
    $response = $this->get('/mentorship')->assertOk();
    if ($role === 'student') $response->assertSee(route('mentors.index'))->assertDontSee(route('mentors.requests'));
    elseif ($role === 'alumni') $response->assertSee(route('mentors.requests'))->assertDontSee(route('mentors.index'));
    else $response->assertDontSee(route('mentors.requests'))->assertDontSee(route('mentors.index'));
})->with(['student','alumni','admin','super_admin']);

test('unknown and forbidden addresses show helpful pages while keeping their error statuses', function () {
    $this->get('/not-a-real-campus-page')->assertNotFound()->assertSee('This page could not be found')->assertSee('Login');
    $this->actingAs($this->student)->get('/admin/dashboard')->assertForbidden()->assertSee('This page needs permission')->assertSee('My Dashboard');
    $this->get('/alumni-mentors')->assertOk();
});

test('profile images retain original portrait dimensions and render without a square crop for every role', function (string $role) {
    $user = User::create(['name'=>'Photo Tester','email'=>$role.'@photo.example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $bytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAMAAAAFCAIAAAAPE8H1AAAAEUlEQVR4nGNwWPABghiIZAEAnbAbMTJBBisAAAAASUVORK5CYII=');
    $this->actingAs($user)->patch(route('profile.update'),['name'=>$user->name,'profile_image'=>UploadedFile::fake()->createWithContent('portrait.png',$bytes)])->assertSessionHasNoErrors()->assertRedirect();
    $user->refresh();
    $image = $this->get($user->getProfileImageUrl())->assertOk()->assertHeader('Content-Type','image/png');
    expect($image->getContent())->toBe($bytes);
    $this->get(route('profile.edit'))->assertOk()->assertSee('data-profile-photo',false)->assertSee('width:auto;height:auto',false)->assertSee('object-fit:contain',false);
    if (in_array($role,['student','alumni'])) {
        $route = $role === 'student' ? 'profiles.student.show' : 'profiles.alumni.show';
        $this->actingAs($this->other)->get(route($route,$user))->assertOk()->assertSee('data-profile-photo',false)->assertSee('width:auto;height:auto',false);
    }
    $this->actingAs($user)->patch(route('profile.update'),['name'=>$user->name,'phone'=>'01700000000'])->assertRedirect();
    expect($user->fresh()->profile_image)->toBe($user->profile_image);
})->with(['student','alumni','admin','super_admin']);

test('old public profile pictures work without a storage link', function () {
    $bytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAMAAAAFCAIAAAAPE8H1AAAAEUlEQVR4nGNwWPABghiIZAEAnbAbMTJBBisAAAAASUVORK5CYII=');
    Storage::disk('public')->put('profiles/old.png',$bytes);
    $this->student->update(['profile_image'=>'profiles/old.png']);
    $this->actingAs($this->mentor)->get($this->student->getProfileImageUrl())->assertOk()->assertHeader('Content-Type','image/png');
});

test('alumni can enter and clear every professional field shown on the public profile', function () {
    $fields = ['current_company'=>'Campus Technology Ltd','current_designation'=>'Software Engineer','current_job_type'=>'Part-time','work_experience_years'=>'0','previous_company'=>'Learning Foundation','previous_designation'=>'Trainee','previous_job_details'=>'Built the campus library system.'];
    $this->actingAs($this->mentor)->get(route('profile.edit'))->assertOk()->assertSee('Professional Summary');
    $this->patch(route('profile.update'),$fields+['name'=>$this->mentor->name])->assertSessionHasNoErrors()->assertRedirect();
    foreach ($fields as $field=>$value) expect($this->mentor->fresh()->$field)->toBe($value);
    $response = $this->actingAs($this->student)->get(route('profiles.alumni.show',$this->mentor))->assertOk();
    foreach ($fields as $value) $response->assertSee($value);
    $this->actingAs($this->mentor)->patch(route('profile.update'),array_fill_keys(array_keys($fields),'')+['name'=>$this->mentor->name])->assertSessionHasNoErrors()->assertRedirect();
    $this->get(route('profiles.alumni.show',$this->mentor))->assertDontSee('Campus Technology Ltd')->assertDontSee('Alumni Professional')->assertDontSee('Professional Alumni');
});

test('profile updates cannot grant permissions or write another roles professional fields', function () {
    $this->actingAs($this->student)->patch(route('profile.update'),['name'=>$this->student->name,'role'=>'super_admin','is_owner'=>1,'is_active'=>false,'current_company'=>'Injected Company'])->assertSessionHasNoErrors()->assertRedirect();
    expect($this->student->fresh()->role)->toBe('student')->and($this->student->fresh()->is_active)->toBeTrue()->and($this->student->fresh()->current_company)->toBeNull();
    expect(app(\App\Services\ProfileStrengthService::class)->analyze(new User(['portfolio_url'=>'https://portfolio.example.com']))['completed'])->toContain('GitHub / Portfolio');
});

test('a previous job with no end date is not assumed to continue until today', function () {
    \App\Models\AlumniWorkExperience::create(['user_id'=>$this->mentor->id,'company_name'=>'Incomplete History','designation'=>'Trainee','start_date'=>today()->subYears(10)->toDateString(),'is_current'=>false]);
    $this->actingAs($this->mentor)->post(route('profile.work-experiences.store'),[
        'company_name'=>'Recorded Employer','designation'=>'Engineer','start_date'=>'2026-01-01','end_date'=>'2026-01-31','is_current'=>false,
    ])->assertSessionHasNoErrors()->assertRedirect();
    expect($this->mentor->fresh()->work_experience_years)->toBe('1 month');
});

test('expiry archives all post types and accepted extensions alone restore the feed', function (string $type) {
    $this->travelTo(now()->setDate(2026, 10, 13)->setTime(12, 0));
    $post = permissionPost($type, $this->mentor);
    $post->update($type === 'event' ? ['start_date'=>'2026-10-11','end_date'=>'2026-10-12','event_date'=>'2026-10-11 10:00'] : ['deadline'=>'2026-10-12']);
    $this->actingAs($this->mentor)->get(route('posts.archive', ['type'=>$type]))->assertOk()->assertSee($post->title);
    expect($post->fresh()->archived_at)->not->toBeNull();
    $this->get(route('newsfeed.index'))->assertOk()->assertDontSee($post->title);
    $dates = $type === 'event' ? ['start_date'=>'2026-11-02','end_date'=>'2026-11-02','start_time'=>'10:00','end_time'=>'12:00'] : ['deadline'=>'2026-11-02'];
    $this->post(route('posts.extension.apply',['type'=>$type,'id'=>$post->id]), $dates + ['reason'=>'More time needed for campus participation.'])->assertSessionHasNoErrors()->assertRedirect();
    $extension = \App\Models\PostExtensionRequest::firstOrFail();
    expect($post->fresh()->archived_at)->not->toBeNull();
    $this->post(route('posts.extension.apply',['type'=>$type,'id'=>$post->id]), $dates + ['reason'=>'Duplicate extension request'])->assertSessionHasErrors();
    $this->patch(route('posts.extension.review',$extension),['decision'=>'approved'])->assertForbidden();
    $admin = User::create(['name'=>'Admin','email'=>'review@example.com','role'=>'admin','is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($admin)->patch(route('posts.extension.review',$extension),['decision'=>'approved'])->assertSessionHasNoErrors()->assertRedirect();
    expect($post->fresh()->archived_at)->toBeNull()->and($extension->fresh()->status)->toBe('approved');
    $this->get(route('newsfeed.index'))->assertOk()->assertSee($post->title);
    $this->patch(route('posts.extension.review',$extension),['decision'=>'approved'])->assertStatus(409);
})->with(['job','event','donation']);

test('only the campaign owner verifies manual payments and amounts count exactly once', function (string $method) {
    $post = permissionPost('donation', $this->mentor);
    $this->actingAs($this->student)->post(route('donations.manual-payment',$post),['payment_method'=>$method,'account_number'=>'01700000000','transaction_id'=>'TX123456','amount'=>250])->assertSessionHasNoErrors()->assertRedirect();
    $payment = \App\Models\DonationManualPayment::firstOrFail();
    expect((float)$post->fresh()->collected_amount)->toBe(500.0);
    $admin = User::create(['name'=>'Admin','email'=>'payment-admin@example.com','role'=>'admin','is_active'=>true,'is_blocked'=>false]);
    foreach ([$this->student, $this->other, $admin] as $viewer) {
        $this->actingAs($viewer)->patch(route('donation-payments.approve',$payment))->assertForbidden();
        $this->get(route('donation-payments.pending'))->assertOk()->assertDontSee('TX123456');
    }
    $this->actingAs($this->mentor)->get(route('donation-payments.pending'))->assertOk()->assertSee('TX123456');
    $this->patch(route('donation-payments.approve',$payment))->assertRedirect();
    $this->patch(route('donation-payments.approve',$payment))->assertRedirect();
    expect((float)$post->fresh()->collected_amount)->toBe(750.0)->and($post->contributions()->count())->toBe(1);
    $this->get(route('donations.show',$post))->assertSee('750.00');
})->with(['bKash','Nagad']);

test('card payment is attributed correctly and callback retries cannot inflate donations', function () {
    $post = permissionPost('donation', $this->mentor);
    config(['services.stripe.currency'=>'bdt']);
    $session = \Stripe\Checkout\Session::constructFrom(['id'=>'cs_verified','mode'=>'payment','payment_status'=>'paid','currency'=>'bdt','amount_total'=>25000,'metadata'=>['donation_id'=>(string)$post->id,'user_id'=>(string)$this->student->id]]);
    $service = app(\App\Services\DonationCardPaymentService::class);
    expect($service->record($session,$post->id,$this->student->id))->toBeTrue();
    expect($service->record($session,$post->id,$this->student->id))->toBeFalse();
    expect((float)$post->fresh()->collected_amount)->toBe(750.0)->and($post->contributions()->count())->toBe(1);
    expect(fn () => $service->record($session,$post->id,$this->other->id))->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

test('archived campaigns cannot accept new payments and unrelated users cannot archive posts', function () {
    $post = permissionPost('donation',$this->mentor);
    $this->actingAs($this->other)->patch(route('posts.archive.store',['type'=>'donation','id'=>$post->id]))->assertForbidden();
    $this->actingAs($this->mentor)->patch(route('posts.archive.store',['type'=>'donation','id'=>$post->id]))->assertRedirect();
    $this->actingAs($this->student)->post(route('donations.manual-payment',$post),[])->assertForbidden();
    $this->post(route('donations.stripe.checkout',$post),['amount'=>100])->assertForbidden();
});

test('rejected payments leave totals unchanged and cannot later be approved', function () {
    $post = permissionPost('donation', $this->mentor);
    $payment = \App\Models\DonationManualPayment::create(['donation_id'=>$post->id,'user_id'=>$this->student->id,'payment_method'=>'bKash','amount'=>250,'transaction_id'=>'REJECTED1','status'=>'pending']);
    $this->actingAs($this->mentor)->patch(route('donation-payments.reject',$payment),['rejection_reason'=>'Transaction was not received.'])->assertRedirect();
    $this->patch(route('donation-payments.approve',$payment))->assertRedirect();
    expect((float)$post->fresh()->collected_amount)->toBe(500.0)->and($post->contributions()->count())->toBe(0)->and($payment->fresh()->status)->toBe('rejected');
    $this->actingAs($this->student)->post(route('donations.contribute',$post),['amount'=>999,'payment_method'=>'bkash'])->assertForbidden();
});

test('only donor and campaign owner can view encrypted payment proof', function () {
    $post = permissionPost('donation',$this->mentor);
    $proof = app(\App\Services\EncryptedFileService::class)->store(UploadedFile::fake()->create('proof.png',1,'image/png'),'donation-payments');
    $payment = \App\Models\DonationManualPayment::create(['donation_id'=>$post->id,'user_id'=>$this->student->id,'status'=>'pending','screenshot'=>$proof]);
    foreach ([$this->student,$this->mentor] as $viewer) $this->actingAs($viewer)->get(route('secure.donation-payments.screenshot',$payment))->assertOk();
    $this->actingAs($this->other)->get(route('secure.donation-payments.screenshot',$payment))->assertForbidden();
});

test('extension rejection and invalid dates cannot republish archives', function () {
    $post = permissionPost('donation',$this->mentor);
    $post->update(['deadline'=>today()->subDays(2),'archived_at'=>now()]);
    $this->actingAs($this->other)->post(route('posts.extension.apply',['type'=>'donation','id'=>$post->id]),['deadline'=>today()->addDays(20)->toDateString(),'reason'=>'Unrelated author extension'])->assertForbidden();
    $this->actingAs($this->mentor)->post(route('posts.extension.apply',['type'=>'donation','id'=>$post->id]),['deadline'=>today()->subDay()->toDateString(),'reason'=>'Expired date extension'])->assertSessionHasErrors('deadline');
    $this->post(route('posts.extension.apply',['type'=>'donation','id'=>$post->id]),['deadline'=>today()->addDays(20)->toDateString(),'reason'=>'Please extend for campus fund collection'])->assertRedirect();
    $extension = \App\Models\PostExtensionRequest::firstOrFail();
    $admin = User::create(['name'=>'Admin','email'=>'reject@example.com','role'=>'super_admin','is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($admin)->patch(route('posts.extension.review',$extension),['decision'=>'rejected','review_note'=>'Insufficient information'])->assertRedirect();
    expect($post->fresh()->archived_at)->not->toBeNull()->and($extension->fresh()->status)->toBe('rejected');
});

test('direct editing cannot bypass date extension review', function () {
    $post = permissionPost('donation',$this->mentor);
    $data = $post->getAttributes(); $data['deadline'] = today()->addDays(30)->toDateString();
    $this->actingAs($this->mentor)->patch(route('posts.update',['type'=>'donation','id'=>$post->id]),$data)->assertSessionHasErrors('deadline');
    expect($post->fresh()->deadline)->toBeNull();
});

test('signed card webhooks update amounts once and invalid signatures are rejected', function () {
    config(['services.stripe.webhook_secret'=>'whsec_test','services.stripe.currency'=>'bdt']);
    $post = permissionPost('donation',$this->mentor);
    $payload = json_encode(['id'=>'evt_test','object'=>'event','type'=>'checkout.session.completed','data'=>['object'=>['id'=>'cs_webhook','object'=>'checkout.session','mode'=>'payment','payment_status'=>'paid','amount_total'=>10000,'currency'=>'bdt','metadata'=>['donation_id'=>(string)$post->id,'user_id'=>(string)$this->student->id]]]]);
    $timestamp = time();
    $signature = 't='.$timestamp.',v1='.hash_hmac('sha256',$timestamp.'.'.$payload,'whsec_test');
    for ($i=0;$i<2;$i++) $this->call('POST',route('donations.stripe.webhook'),[],[],[],['HTTP_STRIPE_SIGNATURE'=>$signature,'CONTENT_TYPE'=>'application/json'],$payload)->assertOk();
    expect((float)$post->fresh()->collected_amount)->toBe(600.0)->and($post->contributions()->count())->toBe(1);
    $this->call('POST',route('donations.stripe.webhook'),[],[],[],['HTTP_STRIPE_SIGNATURE'=>'bad','CONTENT_TYPE'=>'application/json'],$payload)->assertStatus(400);
});

test('each role can open all reachable platform links and button forms use permitted routes', function (string $role) {
    $user = User::create(['name'=>'Navigation Tester','email'=>$role.'@nav.example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $author = in_array($role,['alumni','admin','super_admin']) ? $user : $this->mentor;
    foreach (['job','event','donation'] as $type) permissionPost($type,$author);
    permissionMentorship($this);
    $dashboard = match ($role) { 'super_admin'=>'superadmin.dashboard',default=>$role.'.dashboard' };
    $queue = [route($dashboard)]; $seen = [];
    $this->actingAs($user);
    while ($queue) {
        $url = array_shift($queue);
        $path = parse_url($url,PHP_URL_PATH);
        if (isset($seen[$path])) continue;
        $seen[$path] = true;
        $response = $this->get($url);
        expect($response->status(), $role.' link '.$url)->toBeLessThan(400);
        if ($response->status() >= 300 || !str_contains($response->getContent(), '<html')) continue;
        $html = $response->getContent();
        preg_match_all('/<a\b[^>]*href="([^"]+)"/i', $html, $matches);
        foreach (array_unique($matches[1]) as $next) {
            $next = html_entity_decode($next);
            if (str_starts_with($next,'/')) $next = url($next);
            if (!str_starts_with($next,url('/')) || str_contains($next,'#')) continue;
            $nextPath = parse_url($next,PHP_URL_PATH);
            if (is_file(public_path(ltrim($nextPath,'/'))) || str_starts_with($nextPath,'/secure/')) continue;
            $route = app('router')->getRoutes()->match(\Illuminate\Http\Request::create($nextPath));
            if ($route->getName() === 'logout') continue;
            if (!isset($seen[$nextPath])) $queue[] = $next;
        }
        preg_match_all('/<form\b([^>]*)>(.*?)<\/form>/si',$html,$forms,PREG_SET_ORDER);
        foreach ($forms as $form) {
            if (!preg_match('/action="([^"]+)"/i',$form[1],$action)) continue;
            $action = html_entity_decode($action[1]);
            if (!str_starts_with($action,url('/'))) continue;
            $method = preg_match('/method="([^"]+)"/i',$form[1],$m) ? strtoupper($m[1]) : 'GET';
            if (preg_match('/name="_method"\s+value="([^"]+)"/i',$form[2],$m)) $method = strtoupper($m[1]);
            $route = app('router')->getRoutes()->match(\Illuminate\Http\Request::create($action,$method));
            if (str_contains($form[2],'disabled')) continue;
            foreach ($route->gatherMiddleware() as $middleware) {
                if (str_starts_with($middleware,'role:')) $this->assertContains($role,explode(',',substr($middleware,5)),$role.' form '.$action);
            }
        }
    }
    expect(count($seen))->toBeGreaterThan(10);
})->with(['student','alumni','admin','super_admin']);

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    $this->withoutVite();
    foreach ([User::class, Message::class, Mentorship::class, JobPosting::class, Event::class, Donation::class, Notification::class, \App\Models\EventParticipant::class, \App\Models\DonationContribution::class, \App\Models\DonationManualPayment::class, \App\Models\PostExtensionRequest::class, \App\Models\VerifiedUser::class, \App\Models\AlumniConversionRequest::class, \App\Models\ResumeAnalysis::class, \App\Models\AlumniWorkExperience::class, \App\Models\AISuggestion::class, \App\Models\JobApplication::class, \App\Models\ActivityLog::class] as $class) {
        $model = new $class;
        Schema::create($model->getTable(), function (Blueprint $table) use ($model) {
            $table->id();
            foreach ($model->getFillable() as $column) {
                if (in_array($column, ['is_read', 'is_edited', 'deleted_by_sender', 'deleted_by_receiver'])) {
                    $table->boolean($column)->default(false);
                } elseif (str_ends_with($column, '_id') || in_array($column, ['is_active', 'is_blocked', 'is_owner', 'email_verified'])) {
                    $table->integer($column)->nullable();
                } else {
                    $table->text($column)->nullable();
                }
            }
            $table->timestamps();
        });
    }
    Schema::create('donation_card_payments', function (Blueprint $table) {
        $table->id(); $table->string('session_id')->unique(); $table->integer('donation_id'); $table->integer('user_id'); $table->decimal('amount',12,2); $table->timestamps();
    });
    Storage::fake('local');
    Storage::fake('public');
    $this->student = User::create(['name' => 'Student', 'email' => 'student@example.com', 'role' => 'student', 'is_active' => true, 'is_blocked' => false]);
    $this->mentor = User::create(['name' => 'Mentor', 'email' => 'mentor@example.com', 'role' => 'alumni', 'is_active' => true, 'is_blocked' => false]);
    $this->other = User::create(['name' => 'Other Alumni', 'email' => 'other@example.com', 'role' => 'alumni', 'is_active' => true, 'is_blocked' => false]);
});

function permissionPost(string $type, User $author)
{
    return match ($type) {
        'job' => JobPosting::create(['title' => 'Software internship', 'description' => 'Learn programming', 'company_name' => 'Campus', 'location' => 'Dhaka', 'type' => 'internship', 'experience_level' => 'entry', 'requirements' => 'PHP', 'positions_available' => 2, 'contact_email' => 'jobs@example.com', 'posted_by' => $author->id, 'status' => 'approved']),
        'event' => Event::create(['title' => 'Workshop', 'description' => 'Campus workshop', 'type' => 'workshop', 'location' => 'Campus', 'start_date' => '2026-11-01', 'end_date' => '2026-11-01', 'start_time' => '10:00', 'end_time' => '12:00', 'event_date' => '2026-11-01 10:00', 'created_by' => $author->id, 'status' => 'active']),
        'donation' => Donation::create(['title' => 'Book fund', 'description' => 'Learning support', 'target_amount' => 2000, 'collected_amount' => 500, 'user_id' => $author->id, 'status' => 'approved']),
    };
}

function permissionMentorship($test, string $status = 'accepted')
{
    return Mentorship::create(['student_id' => $test->student->id, 'mentor_id' => $test->mentor->id, 'title' => 'Guidance', 'description' => 'Career advice', 'status' => $status]);
}

test('only post authors or management can edit and delete posts', function (string $type) {
    $post = permissionPost($type, $this->mentor);
    $route = ['type' => $type, 'id' => $post->id];
    $this->actingAs($this->other)->get(route('posts.edit', $route))->assertForbidden();
    $this->patch(route('posts.update', $route), ['title' => 'Tampered'])->assertForbidden();
    $this->delete(route('posts.destroy', $route))->assertForbidden();
    expect($post->fresh()->title)->not->toBe('Tampered');
    $this->actingAs($this->mentor)->get(route('posts.edit', $route))->assertOk()->assertSee('Update Post');
    $this->delete(route('posts.destroy', $route))->assertRedirect(route('newsfeed.index'));
    expect($post->fresh())->toBeNull();
})->with(['job', 'event', 'donation']);

test('authors can update content but cannot change ownership or publish themselves', function (string $type) {
    $post = permissionPost($type, $this->mentor);
    $data = $post->getAttributes();
    if ($type === 'event') {
        $data['start_date'] = $post->start_date->format('Y-m-d');
        $data['end_date'] = $post->end_date->format('Y-m-d');
    }
    $data['title'] = 'Updated title';
    $data['posted_by'] = $this->other->id;
    $data['created_by'] = $this->other->id;
    $data['user_id'] = $this->other->id;
    $data['status'] = 'approved';
    $data['collected_amount'] = 9000;
    $this->actingAs($this->mentor)->patch(route('posts.update', ['type' => $type, 'id' => $post->id]), $data)->assertSessionHasNoErrors()->assertRedirect();
    $fresh = $post->fresh();
    expect($fresh->title)->toBe('Updated title')->and($fresh->status)->toBe('pending');
    expect(\App\Support\PostAccess::ownerId($type, $fresh))->toBe($this->mentor->id);
    if ($type === 'donation') expect((float) $fresh->collected_amount)->toBe(500.0);
})->with(['job', 'event', 'donation']);

test('owners get management controls instead of job application actions', function () {
    $post = permissionPost('job', $this->mentor);
    $this->actingAs($this->mentor)->get(route('jobs.show', $post))->assertOk()->assertSee('Edit / Update')->assertDontSee('How to Apply');
    $this->actingAs($this->other)->get(route('jobs.show', $post))->assertOk()->assertSee('How to Apply')->assertDontSee('Edit / Update');
});

test('other viewers cannot open unpublished event and donation posts', function (string $type) {
    $post = permissionPost($type, $this->mentor);
    $post->update(['status' => 'pending']);
    $name = $type === 'event' ? 'events.show' : 'donations.show';
    $this->actingAs($this->other)->get(route($name, $post))->assertNotFound();
    $this->actingAs($this->mentor)->get(route($name, $post))->assertOk();
})->with(['event', 'donation']);

test('public event details are accessible to students', function () {
    $post = permissionPost('event', $this->mentor);
    $this->actingAs($this->student)->get(route('events.show', $post))->assertOk();
});

test('mentorship must currently be accepted to send or attach messages', function (string $status) {
    permissionMentorship($this, $status);
    $this->actingAs($this->student)->post(route('messages.store', $this->mentor), ['body' => 'Hello', 'attachment' => UploadedFile::fake()->create('notes.txt', 1)]) ->assertForbidden();
    expect(Message::count())->toBe(0)->and(Storage::disk('local')->allFiles())->toBe([]);
    $this->get(route('messages.show', $this->mentor))->assertForbidden();
})->with(['pending', 'rejected', 'completed']);

test('accepted student and mentor can message each other but no other account', function () {
    permissionMentorship($this);
    $this->actingAs($this->student)->post(route('messages.store', $this->mentor), ['body' => 'Please review my CV'])->assertRedirect();
    $this->actingAs($this->mentor)->post(route('messages.store', $this->student), ['body' => 'Yes, send it here'])->assertRedirect();
    $this->actingAs($this->student)->post(route('messages.store', $this->other), ['body' => 'Unrelated'])->assertForbidden();
    expect(Message::count())->toBe(2);
});

test('completed conversations are readable but cannot be edited or resumed', function () {
    permissionMentorship($this, 'completed');
    $message = Message::create(['sender_id' => $this->student->id, 'recipient_id' => $this->mentor->id, 'content' => 'Old conversation']);
    $this->actingAs($this->student)->get(route('messages.show', $this->mentor))->assertOk()->assertSee('Old conversation')->assertSee('Messaging is locked.');
    $this->get(route('messages.index'))->assertOk()->assertSee($this->mentor->name);
    $this->patch(route('messages.update', $message), ['body' => 'Changed'])->assertForbidden();
    expect($message->fresh()->content)->toBe('Old conversation');
});

test('blocked mentorship partners cannot communicate', function () {
    permissionMentorship($this);
    $this->mentor->update(['is_blocked' => true]);
    expect($this->student->canMessage($this->mentor))->toBeFalse();
    $this->actingAs($this->student)->post(route('calls.start', $this->mentor), ['type' => 'audio'])->assertForbidden();
});

test('students and alumni choose interests on first visit and update only their own preferences', function (string $role) {
    $this->student->update(['role' => $role]);
    $this->actingAs($this->student)->get(route('newsfeed.index'))->assertOk()->assertSee('Choose your interests');
    $this->patch(route('newsfeed.interests.update'), ['interests' => ['technology', 'research'], 'user_id' => $this->other->id])->assertRedirect(route('newsfeed.index'));
    expect($this->student->fresh()->interests)->toBe(['technology', 'research'])->and($this->other->fresh()->interests)->toBeNull();
    $this->get(route('newsfeed.index'))->assertSee('Update Interests');
    $this->patch(route('newsfeed.interests.update'), ['interests' => ['culture']])->assertRedirect();
    expect($this->student->fresh()->interests)->toBe(['culture']);
    $this->patch(route('newsfeed.interests.update'), ['interests' => ['unknown']])->assertSessionHasErrors();
    $this->other->update(['role'=>'admin']);
    $this->actingAs($this->other)->patch(route('newsfeed.interests.update'), ['interests' => ['technology']])->assertForbidden();
})->with(['student', 'alumni']);

test('related feed posts rank before newer unrelated posts without hiding them', function (string $role) {
    $related = permissionPost('job', $this->mentor);
    $related->update(['created_at' => now()->subDays(5)]);
    $other = permissionPost('job', $this->mentor);
    $other->update(['title' => 'Sports coach', 'description' => 'Football fitness training', 'requirements' => 'Coaching']);
    $this->student->update(['role' => $role, 'interests' => ['technology']]);
    $response = $this->actingAs($this->student)->get(route('newsfeed.index'))->assertOk();
    $items = $response->viewData('feedItems');
    expect($items->pluck('feedable_id')->all())->toBe([$related->id, $other->id]);
    expect($items->count())->toBe(2);
})->with(['student', 'alumni']);

test('management retains permission to update other authors posts', function (string $role, string $type) {
    $post = permissionPost($type, $this->mentor);
    $admin = User::create(['name' => 'Management', 'email' => 'admin@example.com', 'role' => $role, 'is_active' => true, 'is_blocked' => false]);
    $data = $post->getAttributes();
    $data['title'] = 'Management correction';
    if ($type === 'event') {
        $data['start_date'] = $post->start_date->format('Y-m-d');
        $data['end_date'] = $post->end_date->format('Y-m-d');
    }
    $status = $post->status;
    $this->actingAs($admin)->patch(route('posts.update', ['type' => $type, 'id' => $post->id]), $data)->assertSessionHasNoErrors()->assertRedirect();
    expect($post->fresh()->title)->toBe('Management correction')->and($post->fresh()->status)->toBe($status);
})->with(['admin', 'super_admin'])->with(['job', 'event', 'donation']);

test('donation targets cannot be lowered below money already collected', function () {
    $post = permissionPost('donation', $this->mentor);
    $data = $post->getAttributes();
    $data['target_amount'] = 100;
    $this->actingAs($this->mentor)->patch(route('posts.update', ['type' => 'donation', 'id' => $post->id]), $data)->assertSessionHasErrors('target_amount');
    expect((float) $post->fresh()->target_amount)->toBe(2000.0);
});

test('a retained post image is not erased when editing without a new upload', function () {
    $post = permissionPost('job', $this->mentor);
    Storage::disk('public')->put('jobs/old.png', 'existing image');
    $post->update(['job_image' => 'jobs/old.png']);
    $data = $post->getAttributes();
    unset($data['job_image']);
    $data['title'] = 'Updated internship';
    $this->actingAs($this->mentor)->patch(route('posts.update', ['type' => 'job', 'id' => $post->id]), $data)->assertSessionHasNoErrors();
    expect($post->fresh()->job_image)->toBe('jobs/old.png');
    Storage::disk('public')->assertExists('jobs/old.png');
});

test('completed mentorship cannot be bypassed through the call endpoint', function () {
    permissionMentorship($this, 'completed');
    $this->actingAs($this->student)->post(route('calls.start', $this->mentor), ['type' => 'video'])->assertForbidden();
});

test('malformed interest input remains a validation error and does not break the feed', function () {
    $this->actingAs($this->student)->patch(route('newsfeed.interests.update'), ['interests' => 'invalid'])->assertSessionHasErrors('interests');
    $this->get(route('newsfeed.index'))->assertOk();
});


test('post management stays in details and extension requests are owner initiated', function (string $type) {
    $post = permissionPost($type, $this->mentor);
    $admin = User::create(['name'=>'Extension Reviewer','email'=>'extension-reviewer@example.com','role'=>'admin','is_active'=>true,'is_blocked'=>false]);
    $details = route(match($type) {'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'}, $post);
    $extensionUrl = route('posts.extension.form', ['type'=>$type,'id'=>$post->id]);
    $this->actingAs($admin)->get(route('newsfeed.index'))->assertOk()->assertDontSee('Apply Date Extension')->assertDontSee('Move to Archive')->assertDontSee('Edit / Update');
    $this->get($details)->assertOk()->assertSee('Edit / Update')->assertDontSee('Apply Date Extension')->assertDontSee('Approve & Publish', false);
    $this->get($extensionUrl)->assertForbidden();
    $this->post(route('posts.extension.apply',['type'=>$type,'id'=>$post->id]), ['reason'=>'Manager must not apply'])->assertForbidden();
    $post->update(['archived_at'=>now()]);
    $this->get(route('posts.archive',['type'=>$type]))->assertOk()->assertDontSee($post->title)->assertDontSee('Archived Posts');
    $this->actingAs($this->mentor)->get($details)->assertOk()->assertSee('Apply Date Extension');
    $extension = \App\Models\PostExtensionRequest::create(['post_type'=>$type,'post_id'=>$post->id,'user_id'=>$this->mentor->id,'dates'=>['deadline'=>today()->addMonths(2)->toDateString()],'reason'=>'Additional time for campus participation','status'=>'pending']);
    $this->get($details)->assertOk()->assertSee('Extension Pending')->assertDontSee('Apply Date Extension')->assertDontSee('Approve & Publish', false);
    $this->get($extensionUrl)->assertStatus(409);
    $this->actingAs($admin)->get($details)->assertOk()->assertSee('Approve & Publish', false)->assertSee($extension->reason)->assertDontSee('Apply Date Extension');
    $this->get(route('posts.archive',['type'=>$type]))->assertOk()->assertSee($extension->reason)->assertSee('Approve & Publish', false);
    $this->actingAs($this->other)->get($details)->assertOk()->assertDontSee('Apply Date Extension')->assertDontSee('Approve & Publish', false)->assertDontSee('Manage Post');
})->with(['job','event','donation']);


test('manual archive confirms success on details and removes active posts', function (string $role, string $type) {
    $author = $this->mentor;
    $actor = $role === 'alumni' ? $author : User::create(['name'=>'Archive Manager','email'=>'archive-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $post = permissionPost($type, $author);
    $details = route(match($type) {'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'}, $post);
    $this->actingAs($this->other)->patch(route('posts.archive.store',['type'=>$type,'id'=>$post->id]))->assertForbidden();
    $this->actingAs($actor)->get($details)->assertOk()->assertSee('Move to Archive');
    $this->patch(route('posts.archive.store',['type'=>$type,'id'=>$post->id]))->assertRedirect($details)->assertSessionHas('success');
    expect($post->fresh()->archived_at)->not->toBeNull();
    $this->get($details)->assertOk()->assertSee('Post archived successfully.')->assertSee('Already Archived')->assertSee('Status: Archived')->assertDontSee('Move to Archive');
    $this->get(route('newsfeed.index'))->assertOk()->assertDontSee($post->title);
    $this->get(route(match($type) {'job'=>'jobs.index','event'=>'events.index','donation'=>'donations.index'}))->assertOk()->assertDontSee($post->title);
    if ($role === 'alumni') $this->get(route('posts.archive',['type'=>$type]))->assertOk()->assertSee($post->title);
})->with(['alumni','admin','super_admin'])->with(['job','event','donation']);


test('feed detail links open individual posts with role appropriate actions', function (string $role, string $type) {
    $post = permissionPost($type, $this->mentor);
    $actor = match ($role) {
        'owner' => $this->mentor,
        'student' => $this->student,
        'alumni' => $this->other,
        default => User::create(['name'=>'Detail Manager','email'=>'detail-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]),
    };
    $details = route(match($type) {'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'}, $post);
    $feed = $this->actingAs($actor)->get(route('newsfeed.index'))->assertOk();
    $item = $feed->viewData('feedItems')->first(fn ($item) => $item['type'] === $type && $item['feedable_id'] === $post->id);
    expect($item['url'])->toBe($details);
    $response = $this->get($item['url'])->assertOk()->assertViewIs(match($type) {'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'})->assertSee($post->title)->assertSee($post->description);
    if (in_array($role, ['owner','admin','super_admin'], true)) {
        $response->assertSee('Edit / Update')->assertSee('Manage Post');
        if ($role === 'owner') $response->assertSee('Apply Date Extension');
        else $response->assertDontSee('Apply Date Extension')->assertDontSee('Approve & Publish', false);
    } else $response->assertDontSee('Edit / Update')->assertDontSee('Manage Post');
    if ($type === 'job') {
        if (in_array($role, ['student','alumni'], true)) $response->assertSee('How to Apply');
        else $response->assertDontSee('How to Apply');
    }
    if ($type === 'event') {
        if (in_array($role, ['student','alumni'], true)) $response->assertSee('action="'.route('events.register',$post).'"', false);
        else $response->assertDontSee('action="'.route('events.register',$post).'"', false);
    }
    if ($type === 'donation') {
        if (in_array($role, ['student','alumni'], true)) $response->assertSee('selectDonationMethod');
        else $response->assertDontSee('id="payment-panel-bkash"', false);
    }
})->with(['student','alumni','owner','admin','super_admin'])->with(['job','event','donation']);


test('deleted posts disappear from fresh feeds and stale links return safely', function (string $role, string $type) {
    $post = permissionPost($type, $this->mentor);
    $actor = $role === 'owner' ? $this->mentor : User::create(['name'=>'Delete Manager','email'=>'delete-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $details = route(match($type) {'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'}, $post);
    $unrelated = permissionPost($type, $this->other);
    $this->actingAs($actor)->get(route('newsfeed.index'))->assertOk()->assertSee($details, false);
    $this->delete(route('posts.destroy',['type'=>$type,'id'=>$post->id]))->assertRedirect(route('newsfeed.index'));
    expect($post->fresh())->toBeNull();
    $feed = $this->get(route('newsfeed.index'))->assertOk()->assertDontSee($details, false);
    expect($feed->viewData('feedItems')->contains(fn ($item) => $item['type'] === $type && $item['feedable_id'] === $post->id))->toBeFalse();
    expect($feed->viewData('feedItems')->contains(fn ($item) => $item['type'] === $type && $item['feedable_id'] === $unrelated->id))->toBeTrue();
    expect($feed->headers->get('Cache-Control'))->toContain('no-store');
    $this->get($details)->assertRedirect(route('newsfeed.index'))->assertSessionHas('success', 'This post is no longer available. It may have been deleted.');
})->with(['owner','admin','super_admin'])->with(['job','event','donation']);


test('provided job contacts remain visible regardless of viewer or archive status', function (string $role, string $state) {
    $post = permissionPost('job', $this->mentor);
    $post->update(['contact_email'=>null,'contact_phone'=>'01712345678','status'=>$state === 'pending' ? 'pending' : 'approved','archived_at'=>$state === 'archived' ? now() : null]);
    $actor = match ($role) {
        'owner'=>$this->mentor,
        'student'=>$this->student,
        'alumni'=>$this->other,
        default=>User::create(['name'=>'Contact Reviewer','email'=>'contact-reviewer@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]),
    };
    $response = $this->actingAs($actor)->get(route('jobs.show',$post));
    if ($state === 'pending' && in_array($role,['student','alumni'],true)) {
        $response->assertNotFound();
        return;
    }
    $response->assertOk()->assertSee('01712345678')->assertSee('tel:01712345678', false)->assertDontSee('Contact information not provided');
    if ($state === 'active' && in_array($role,['student','alumni'],true)) $response->assertSee('How to Apply');
    else $response->assertSee('Contact Details')->assertDontSee('How to Apply');
    $post->update(['contact_phone'=>null]);
    $this->get(route('jobs.show',$post))->assertOk()->assertSee('Contact information not provided');
})->with(['owner','admin','super_admin','student','alumni'])->with(['active','pending','archived']);


test('students and alumni can view and cancel only their own event participation', function (string $role, string $status) {
    $post = permissionPost('event', $this->mentor);
    $this->student->update(['role'=>$role]);
    $participant = \App\Models\EventParticipant::create(['event_id'=>$post->id,'user_id'=>$this->student->id,'status'=>$status]);
    $other = \App\Models\EventParticipant::create(['event_id'=>$post->id,'user_id'=>$this->other->id,'status'=>'approved']);
    $url = route('events.participation.cancel',$post);
    $details = route('events.show',$post);
    $this->actingAs($this->student)->get(route('events.index'))->assertOk()->assertSee($details, false)->assertSee('View Details')->assertSee($url, false)->assertSee('Cancel Participation');
    $this->get($details)->assertOk()->assertSee('Cancel Participation')->assertDontSee('Manage Participants');
    $this->from($details)->delete($url)->assertRedirect($details)->assertSessionHas('success');
    expect($participant->fresh())->toBeNull()->and($other->fresh())->not->toBeNull();
    $this->get($details)->assertOk()->assertDontSee('Cancel Participation')->assertSee('action="'.route('events.register',$post).'"',false);
    $this->post(route('events.register',$post))->assertSessionHasNoErrors();
    expect(\App\Models\EventParticipant::where('event_id',$post->id)->where('user_id',$this->student->id)->value('status'))->toBe('approved');
})->with(['student','alumni'])->with(['pending','approved']);

test('event management is shown to administrators instead of participation actions', function (string $role) {
    $post = permissionPost('event', $this->mentor);
    $participant = \App\Models\EventParticipant::create(['event_id'=>$post->id,'user_id'=>$this->student->id,'status'=>'pending']);
    $admin = User::create(['name'=>'Event Manager','email'=>'event-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($admin)->get(route('events.index'))->assertOk()->assertSee('View / Manage Event')->assertSee(route('events.show',$post),false)->assertDontSee('Cancel Participation');
    $this->get(route('events.show',$post))->assertOk()->assertSee('Registered Participants')->assertSee(route('events.participants',$post),false)->assertDontSee('Cancel Participation');
    $this->get(route('events.participants',$post))->assertOk()->assertSee('Cancel Participation');
    $this->delete(route('events.participation.cancel',$post))->assertForbidden();
    expect($participant->fresh())->not->toBeNull();
    $this->patch(route('event.participants.approve',$participant))->assertSessionHasNoErrors();
    expect($participant->fresh()->status)->toBe('approved');
})->with(['admin','super_admin']);

test('event cancellation does not remove another persons registration', function () {
    $post = permissionPost('event', $this->mentor);
    $participant = \App\Models\EventParticipant::create(['event_id'=>$post->id,'user_id'=>$this->student->id,'status'=>'pending']);
    $this->actingAs($this->other)->delete(route('events.participation.cancel',$post))->assertNotFound();
    expect($participant->fresh())->not->toBeNull();
    $this->actingAs($this->mentor)->get(route('events.index'))->assertOk()->assertSee('Your Event')->assertDontSee('Register for Event');
    $this->post(route('events.register',$post))->assertForbidden();
});


test('event capacity shows full before expiry and cancellation releases a place', function () {
    $event = permissionPost('event', $this->mentor);
    $event->update(['capacity'=>1]);
    $participant = \App\Models\EventParticipant::create(['event_id'=>$event->id,'user_id'=>$this->student->id,'status'=>'pending']);
    $this->actingAs($this->other)->get(route('events.index'))->assertOk()->assertDontSee('Participation Full');
    $participant->update(['status'=>'approved']);
    $this->get(route('events.index'))->assertOk()->assertSee('Participation Full')->assertSee('All places are filled.');
    $this->get(route('events.show',$event))->assertOk()->assertSee('Participation Full')->assertDontSee('action="'.route('events.register',$event).'"',false);
    $this->post(route('events.register',$event))->assertSessionHasErrors('event');
    $this->actingAs($this->student)->get(route('events.show',$event))->assertOk()->assertSee('Participation Full')->assertSee('Cancel Participation');
    $this->delete(route('events.participation.cancel',$event))->assertRedirect();
    $this->actingAs($this->other)->get(route('events.index'))->assertOk()->assertDontSee('Participation Full');
    $this->get(route('events.show',$event))->assertOk()->assertDontSee('Participation Full')->assertSee('action="'.route('events.register',$event).'"',false);
});


test('event owner and management see role based participant contact details only', function (string $role) {
    $event = permissionPost('event', $this->mentor);
    $this->student->update(['official_id'=>'STUDENT-123','student_id'=>'OLD-STUDENT-ID','phone'=>'01711112222']);
    $this->other->update(['official_id'=>null,'alumni_id'=>'ALUMNI-456','phone'=>'01833334444']);
    foreach ([$this->student,$this->other] as $registrant) \App\Models\EventParticipant::create(['event_id'=>$event->id,'user_id'=>$registrant->id,'status'=>'pending']);
    $actor = match ($role) {
        'owner'=>$this->mentor,
        'student'=>$this->student,
        'alumni'=>$this->other,
        default=>User::create(['name'=>'Participant Manager','email'=>'participant-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]),
    };
    $response = $this->actingAs($actor)->get(route('events.participants',$event));
    if (in_array($role,['owner','admin','super_admin'],true)) {
        $response->assertOk()->assertSee('Registered Participants')->assertSee('STUDENT-123')->assertDontSee('OLD-STUDENT-ID')->assertSee('ALUMNI-456')->assertSee('01711112222')->assertSee('01833334444')->assertSee($this->student->email)->assertSee($this->other->email);
        if ($role === 'owner') $response->assertDontSee('Approve Participation');
    } else {
        $response->assertForbidden()->assertDontSee('Registered Participants')->assertDontSee('STUDENT-123')->assertDontSee('ALUMNI-456')->assertDontSee('01711112222')->assertDontSee('01833334444');
    }
})->with(['owner','admin','super_admin','student','alumni']);


test('event registrations are confirmed immediately within available capacity', function (string $role) {
    $event = permissionPost('event',$this->mentor);
    $event->update(['capacity'=>1]);
    $this->student->update(['role'=>$role]);
    $this->actingAs($this->student)->post(route('events.register',$event))->assertSessionHasNoErrors()->assertSessionHas('success','Registration confirmed. You are registered for this event.');
    $participant = \App\Models\EventParticipant::where('event_id',$event->id)->where('user_id',$this->student->id)->firstOrFail();
    expect($participant->status)->toBe('approved');
    $this->get(route('events.index'))->assertOk()->assertSee('Registered')->assertDontSee('Registration Pending')->assertSee('Participation Full');
    $this->post(route('events.register',$event))->assertSessionHasErrors('event');
    $this->actingAs($this->other)->post(route('events.register',$event))->assertSessionHasErrors('event');
    expect($event->participants()->count())->toBe(1);
    $this->actingAs($this->student)->delete(route('events.participation.cancel',$event))->assertRedirect();
    $this->actingAs($this->other)->post(route('events.register',$event))->assertSessionHasNoErrors();
})->with(['student','alumni']);


test('direct event registration works without legacy approval metadata columns', function () {
    \Illuminate\Support\Facades\Schema::table('event_participants', function ($table) {
        $table->dropColumn(['approved_at','approved_by']);
    });
    $event = permissionPost('event',$this->mentor);
    $this->actingAs($this->student)->post(route('events.register',$event))->assertSessionHasNoErrors()->assertRedirect();
    expect(\App\Models\EventParticipant::where('event_id',$event->id)->where('user_id',$this->student->id)->value('status'))->toBe('approved');
    $this->get(route('events.index'))->assertOk()->assertSee('Registered');
});


test('event participant management is scoped and cancellation notifies only the affected user', function (string $role) {
    $event = permissionPost('event',$this->mentor);
    $otherEvent = permissionPost('event',$this->other);
    $this->student->update(['phone'=>'01722223333']);
    $participant = \App\Models\EventParticipant::create(['event_id'=>$event->id,'user_id'=>$this->student->id,'status'=>'approved']);
    $outsider = \App\Models\EventParticipant::create(['event_id'=>$otherEvent->id,'user_id'=>$this->other->id,'status'=>'approved']);
    $actor = $role === 'owner' ? $this->mentor : User::create(['name'=>'Participant Reviewer','email'=>'participant-reviewer@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($actor)->get(route('events.participants',$event))->assertOk()->assertSee('01722223333')->assertDontSee($this->other->email);
    $this->delete(route('events.participants.cancel',[$event,$outsider->id]))->assertNotFound();
    expect($outsider->fresh())->not->toBeNull();
    $this->delete(route('events.participants.cancel',[$event,$participant->id]))->assertRedirect(route('events.participants',$event));
    expect($participant->fresh())->toBeNull();
    $notification = \App\Models\Notification::where('type','event_participation_cancelled')->firstOrFail();
    expect((int)$notification->user_id)->toBe($this->student->id)->and((int)$notification->actor_id)->toBe($actor->id)->and($notification->message)->toContain($event->title)->and($notification->action_url)->toBe(route('events.show',$event));
    $this->delete(route('events.participants.cancel',[$event,$participant->id]))->assertNotFound();
    expect(\App\Models\Notification::where('type','event_participation_cancelled')->count())->toBe(1);
    $this->actingAs($this->other)->get(route('events.participants',$event))->assertForbidden();
    $this->delete(route('events.participants.cancel',[$event,$outsider->id]))->assertForbidden();
})->with(['owner','admin','super_admin']);


test('donation management isolates campaigns and confirms manual payments only once', function () {
    $campaign = permissionPost('donation',$this->mentor);
    $otherCampaign = permissionPost('donation',$this->other);
    $payment = \App\Models\DonationManualPayment::create(['donation_id'=>$campaign->id,'user_id'=>$this->student->id,'payment_method'=>'bKash','account_number'=>'01712345678','transaction_id'=>'CAMPAIGN-TXN','amount'=>100,'status'=>'pending']);
    \App\Models\DonationManualPayment::create(['donation_id'=>$otherCampaign->id,'user_id'=>$this->student->id,'payment_method'=>'Nagad','account_number'=>'01812345678','transaction_id'=>'OTHER-CAMPAIGN-TXN','amount'=>200,'status'=>'pending']);
    $management = route('donations.management',$campaign);
    $this->actingAs($this->mentor)->get($management)->assertOk()->assertSee('CAMPAIGN-TXN')->assertDontSee('OTHER-CAMPAIGN-TXN')->assertSee('Confirm Payment');
    expect((float)$campaign->fresh()->collected_amount)->toBe(500.0);
    $this->from($management)->patch(route('donation-payments.approve',$payment))->assertRedirect($management);
    expect((float)$campaign->fresh()->collected_amount)->toBe(600.0);
    $this->get($management)->assertOk()->assertSee('Confirmed / Checked')->assertDontSee('Confirm Payment')->assertSee($this->student->name);
    $this->patch(route('donation-payments.approve',$payment))->assertRedirect();
    expect((float)$campaign->fresh()->collected_amount)->toBe(600.0)->and(\App\Models\DonationContribution::where('donation_id',$campaign->id)->count())->toBe(1);
    foreach ([$this->student,$this->other] as $viewer) {
        $this->actingAs($viewer)->get($management)->assertForbidden();
        $this->get(route('donations.show',$campaign))->assertOk()->assertDontSee('Donation Management / Transactions')->assertSee('id="payment-panel-bkash"',false);
    }
});

test('administrators inspect donation transactions without changing owner payment verification', function (string $role) {
    $campaign = permissionPost('donation',$this->mentor);
    $payment = \App\Models\DonationManualPayment::create(['donation_id'=>$campaign->id,'user_id'=>$this->student->id,'payment_method'=>'Nagad','account_number'=>'01712345678','transaction_id'=>'OWNER-VERIFIES','amount'=>100,'status'=>'pending']);
    $admin = User::create(['name'=>'Donation Manager','email'=>'donation-manager@example.com','role'=>$role,'is_active'=>true,'is_blocked'=>false]);
    $this->actingAs($admin)->get(route('donations.management',$campaign))->assertOk()->assertSee('OWNER-VERIFIES')->assertSee('Awaiting campaign owner verification.')->assertDontSee('Confirm Payment');
    $this->patch(route('donation-payments.approve',$payment))->assertForbidden();
    expect((float)$campaign->fresh()->collected_amount)->toBe(500.0);
})->with(['admin','super_admin']);


test('post feature lists paginate six posts without empty grid items', function (string $type) {
    for ($i=1; $i<=7; $i++) {
        $post = permissionPost($type,$this->mentor);
        $post->update(['title'=>'Pagination Post '.$i]);
    }
    $route = match($type) {'job'=>'jobs.index','event'=>'events.index','donation'=>'donations.index'};
    $key = match($type) {'job'=>'jobs','event'=>'events','donation'=>'donations'};
    $first = $this->actingAs($this->student)->get(route($route))->assertOk();
    $posts = $first->viewData($key);
    expect($posts->perPage())->toBe(6)->and($posts->count())->toBe(6)->and($posts->total())->toBe(7)->and($posts->lastPage())->toBe(2);
    $second = $this->get(route($route,['page'=>2]))->assertOk();
    $next = $second->viewData($key);
    expect($next->count())->toBe(1)->and($posts->pluck('id')->intersect($next->pluck('id'))->count())->toBe(0);
    if ($type === 'event') $first->assertDontSee('<span id="event-list">',false);
    if ($type === 'donation') expect($second->viewData('stats')['total'])->toBe(7);
})->with(['job','event','donation']);
