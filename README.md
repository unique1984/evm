# evm
Eazy Virtualbox Management

Not: bu yazılımın amacı sadece "**vboxmanage**" komutunun kullanımını kolaylaştırmaktır. "remote" argümanı ile birden fazla VirtualBox Host makinasını yönetmek, izlemek gibi bir niyetim olduğundan yazıyorum web tabanlı isteyenler virtualbox web sitesinden php apiyi indirebilir. Ek olarak çalıştırılan sanal makineler **--headless** uzantısı ile çalıştırılmakta, yani ekranda ek bir pencere görüntülenmeyecek. Son olarak kullanacak veya test edecek arkadaşlara ufak bir uyarı RAM miktarını göz önünde bulundurarak start komutunu kullanın :) 

Hazırlık:

Debian 8/9 üzerinde test edildi.

1 - VirtualBox 5.1.22 kurulumu ile test edildi kurmak için;
`sudo apt-get install virtualbox-5.1`

2 - php5.6 ile test edildi, php yüklü değilse kurmak için;
`sudo apt-get install php5`

3 - `sudo apt-get install bash-completion` 
komutu ile kurulu değilse bash-completion paketini kurun

Kurulum:

4 - yukarıdaki adresten indirdiğiniz 3 dosyayı **~/sh** veya kendi istediğiniz bir lokasyona kopyalayın.

5 - **evm** ve **evm_bash_completion** dosyaları içerisinde **PARSER** değişkenini scriptleri kopyaladığınız yere göre tekrar tanımlayın.

6 - Çalıştırılabilir yapmak için;
`chmod +x ~/sh/evm`
komutunu kullanarak dosyayı çalıştırılabilir yapın.

7 - **export PATH=$PATH":$HOME/sh"** satırını **~/.bashrc** dosyasına ekleyin ve ardından;
`source ~/.bashrc `
komutunu çalıştırarak konsolda evm yazıldığında scriptin çalışmasını sağlayın.

8 - `sudo cp ~/sh/evm_bash_completion /etc/bash_completion.d/evm_bash_completion`
komutu ile tab ile tamamlama modülünü sisteme ekleyin ardından;
`source /etc/bash_completion.d/evm_bash_completion`
komutu ile sisteme tanımlayın.

Kullanım:

9 - Şu an itibariyle
`evm`
komutunu verip enter tuşladığınızda sistemde sanal makina mevcutsa resimdeki gibi bir görüntü almalısınız program kendini kullandıracaktır.

![evm2](https://user-images.githubusercontent.com/3167656/30484148-23773a5c-9a32-11e7-80e7-cf8542ee5308.png)
![output_1st_screen_0](https://user-images.githubusercontent.com/3167656/30518696-12a38066-9b8e-11e7-8221-637edbe13f81.gif)

Kolay gelsin.
