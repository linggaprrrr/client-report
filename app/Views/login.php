<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Report Management System | Login Page</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background-image: linear-gradient(90deg, #54548b 35%, #232356 100%);">
    <div class="grid place-items-center h-screen">        
        <div class="grid grid-cols-2 gap-2">
            <div style="text-align: -webkit-center;">
                <div class="max-w-sm p-4 mt-5 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <img src="/assets/images/fba-logo.png" alt="Image" class="img-fluid">
                </div>
                
                <img src="/assets/images/login-wallpaper.png" style="width: 75%;">
            </div>
            <div style="padding-right: 25%; padding-left: 25%; align-self: center;">  
                <div class="mb-12">
                    <h1 class="text-white text-6xl">Welcome Back</h1>              
                    <p class="text-white text-lg mt-4">Login to your account</p>
                </div>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-2" role="alert">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                        <p><?= session()->getFlashdata('error') ?></p>
                    </div>
                <?php endif ?>
                <form action="/login-proccess" method="POST">
                    <div class="mb-6">
                        <label for="username" class="block text-white mb-2 text-sm font-medium text-gray-900 dark:text-white">Your username</label>
                        <?php if (isset($_COOKIE["sw-username"])) : ?>                            
                            <input type="text" name="username" value="<?= $_COOKIE["sw-username"] ?>" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required>
                        <?php else :  ?>                                                    
                            <input type="text" name="username" value="" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required>
                        <?php endif ?>
                        
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-white mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                        <?php if (isset($_COOKIE["sw-pw"])) : ?>                                        
                            <input type="password" id="password"  name="password" value="<?= $_COOKIE["sw-pw"] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <?php else :  ?>                                        
                            <input type="password" id="password"  name="password" value="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <?php endif ?>                        
                    </div>
                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" name="rememberme" checked="checked" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required>
                        </div>
                        <label for="remember" class="ml-2 text-white text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                    </div>
                    <input type="hidden" name="current" id="" value="<?= base_url(uri_string()) ?>">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" style="width: 100%;">Login</button>
                </form>
                <div>
                    <div style="text-align: center; padding-top: 40px;padding-left: 5px; display: flex; justify-content: center;">
                        <a href="https://apps.apple.com/id/app/smart-fba-client-portal/id1618568127" target="_blink" style="margin-right: 10px"><img src="/assets/images/appstore.png" style="max-width: 160px;"></a>
                        <a href="https://play.google.com/store/apps/details?id=smartfba.app.smartfbaclientportal" target="_blink"><img src="/assets/images/available-google-play.png" style="max-width: 172px; max-height: 53px"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>