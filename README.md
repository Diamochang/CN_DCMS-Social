手机网站的内容管理系统

安装要求：

PHP5.4-7.0及以上（推荐PHP5）、MySQLi、mod_rewrite支持

•如果您能够在免费托管中安装和充分使用引擎，请在项目官方网站的论坛上报告：http://dcms-social.ru/forum/ 

推荐库（如果没有这些库，可能会缺少一些功能）：

1）iconv

2）FFMPEG

3）GD

4）mcrypt

包含模块：

1）聊天（聪明人+1000个问题，笑话+1000个笑话）。

2）论坛（2层，附加文件，搜索，书签）。

3）下载中心（无限数量的子文件夹、上传、导入、截图，评论，直接到文件的下载计数器。）

4）文件交换（正确支持中文文件和文件夹名称，无限子文件夹数量、屏幕截图、文件信息、自定义设置每个文件夹的上传）。

5）图书馆

6）RSS新闻

7）访客

8）投票系统

主要文件夹和引擎文件：

•附加到论坛的文件：sys/forum/files/（*.frf）

•交换机文件：files/down//（*.DAT）

•主题：style/themes/（主题文件夹）

•网站规则：sys/add/rules.txt

•默认主题存档：sys/add/them.zip（用于通过管理员安装主题时替换丢失的主题文件）

安装：

1）创建MySQL数据库（是数据库，而不是表）。

2）将所有文件上载到根目录或子域文件夹。（引擎不会在子文件夹中工作）。

3）访问http://[您的网站]/install/

4）遵循所有安装步骤。

5）如果您在下一步安装方面遇到困难，或者您对引擎的改进有任何建议，请访问我们的论坛http://dcms.net.cn/forum/

额外的模块可以手动下载和安装。

如果您对开发引擎感兴趣，可以通过

请向论坛申请编写模块的订单。

待办事项
删除代码version_stable()
删除token代码