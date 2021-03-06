# git quick reference #
##什么是git
Git是分布式版本控制系统
##安装Git
    先从Git官网下载源码，然后解压，依次输入：
    ./config，make，sudo make install
    安装完成
    $ git config --global user.name "Your Name" 你的名字
    $ git config --global user.email "email@example.com" 你的Email
	#注意git config命令的--global参数，用了这个参数，
	#表示你这台机器上所有的Git仓库都会使用这个配置
##创建版本库
    repository可以理解为目录,git追踪里头目录及文件的变更，通过git init Git就把仓库建好了,
    目录里头会多出.git 
    $ git init
    Initialized empty Git repository in /Users/michael/learngit/.git/
    把文件添加到版本库
    第一步，用命令git add告诉Git，把文件添加到仓库：
    $ git add readme.txt
    第二步，用命令git commit告诉Git，把文件提交到仓库：
    $ git commit -m "wrote a readme file"
## 时光机穿梭
    git status 命令可以让我们时刻掌握仓库当前的状态
    git diff   工作区和暂存区比较
    #要随时掌握工作区的状态，使用git status命令。
    #如果git status告诉你有文件被修改过，用git diff可以查看修改内容。
## 版本回退
    git log 可以查看提交历史，以便确定要回退到哪个版本
    git log --pretty=oneline 查看简要信息
    每提交一个新版本，实际上Git就会把它们自动串成一条时间线。
    通过commit id（版本号）去回退到指定的版本
    Git当前版本  HEAD 
    上一个版本就是   HEAD^
    上上一个版本就是 HEAD^^
    往上100个版本HEAD~100
    git reset命令 回退到指定版本
    git reset --hard HEAD^  
    回退后反悔 通过git reflog查看命令操作日志，找到commit_id
    git reflog 
    
    HEAD指向的版本就是当前版本，因此，Git允许我们在版本的历史之间穿梭，
	使用命令git reset --hard commit_id。
    穿梭前，用git log可以查看提交历史，以便确定要回退到哪个版本。
    要重返未来，用git reflog查看命令历史，以便确定要回到未来的哪个版本。
## 工作区和暂存区
    工作区（Working Directory）：就是你在电脑里能看到的目录
    版本库（Repository）：工作区有一个隐藏目录“.git”，这个不算工作区，而是Git的版本库。
    Git的版本库里存了很多东西，其中最重要的就是称为stage（或者叫index）的暂存区，
	还有Git为我们自动创建的第一个分支master，以及指向master的一个指针叫HEAD。
## 管理修改
    $git diff HEAD -- readme.txt 可以查看工作区和版本库里面最新版本的区别
## 撤销修改
    场景1：当你改乱了工作区某个文件的内容，想直接丢弃工作区的修改时，用命令git checkout -- file。
    场景2：当你不但改乱了工作区某个文件的内容，还添加到了暂存区时，想丢弃修改，
	分两步，第一步用命令git reset HEAD file，就回到了场景1，第二步按场景1操作。
    场景3：已经提交了不合适的修改到版本库时，想要撤销本次提交，
	参考版本回退一节，不过前提是没有推送到远程库。
## 删除文件
    git rm用于删除一个文件。
    如果一个文件已经被提交到版本库，那么你永远不用担心误删，
	但是要小心，你只能恢复文件到最新版本，你会丢失最近一次提交后你修改的内容。
## 远程仓库
    第1步：创建SSH Key。在用户主目录下，看看有没有.ssh目录，如果有，
	再看看这个目录下有没有id_rsa和id_rsa.pub这两个文件，如果已经有了，可直接跳到下一步。
	如果没有，打开Shell（Windows下打开Git Bash），创建SSH Key：
    $ ssh-keygen -t rsa -C "youremail@example.com"
## 从远程库克隆
	远程库已经准备好了，下一步是用命令git clone克隆一个本地库：
    $ git clone git@github.com:michaelliao/gitskills.git
## 分支管理
    首先，我们创建dev分支，然后切换到dev分支：
    $ git checkout -b dev
    git checkout命令加上-b参数表示创建并切换，相当于以下两条命令：
    $ git branch dev
    $ git checkout dev
    然后，用git branch命令查看当前分支：
    $ git branch
    * dev
      master
    此时可以在分支上进行操作
    dev分支的工作完成，我们就可以切换回master分支：
    $ git checkout master
    git merge命令用于合并指定分支到当前分支。
    合并完成后，就可以放心地删除dev分支了：
    $ git branch -d dev

	Git鼓励大量使用分支：
	查看分支：				git branch
	创建分支：				git branch name
	切换分支：				git checkout name
	创建+切换分支：			git checkout -b name
	合并某分支到当前分支：  git merge name
	删除分支：				git branch -d name
	①③解决冲突
	当Git无法自动合并分支时，就必须首先解决冲突。解决冲突后，再提交，合并完成。
	用git log --graph命令可以看到分支合并图。
	Git用<<<<<<<，=======，>>>>>>>标记出不同分支的内容。
	git log --graph --pretty=oneline --abbrev-commit

## 分支管理策略
    Git分支十分强大，在团队开发中应该充分应用。
    合并分支时，加上--no-ff参数就可以用普通模式合并，合并后的历史有分支，
	能看出来曾经做过合并，而fast forward合并就看不出来曾经做过合并。
    在实际开发中，我们应该按照几个基本原则进行分支管理：
    首先，master分支应该是非常稳定的，也就是仅用来发布新版本，平时不能在上面干活；
    那在哪干活呢？干活都在dev分支上，也就是说，dev分支是不稳定的，
	到某个时候，比如1.0版本发布时，再把dev分支合并到master上，在master分支发布1.0版本；
    你和你的小伙伴们每个人都在dev分支上干活，每个人都有自己的分支，时不时地往dev分支上合并就可以了。
## Bug分支
    修复bug时，我们会通过创建新的bug分支进行修复，然后合并，最后删除；
    当手头工作没有完成时，先把工作现场git stash一下，然后去修复bug，修复后，再git stash pop，回到工作现场。
## Feature分支
    开发一个新feature，最好新建一个分支；
    如果要丢弃一个没有被合并过的分支，可以通过git branch -D name强行删除。
## 多人协作
    要查看远程库的信息，用git remote：
    E:\GitHub\note [master +0 ~1 -0]> git remote
    origin
    E:\GitHub\note [master +0 ~1 -0]> git remote -v
    origin  git@github.com:hepanming007/note.git (fetch)
    origin  git@github.com:hepanming007/note.git (push)
    E:\GitHub\note [master +0 ~1 -0]>
    推送分支
    $ git push origin master
    
    master分支是主分支，因此要时刻与远程同步；
    dev分支是开发分支，团队所有成员都需要在上面工作，所以也需要与远程同步；
    bug分支只用于在本地修复bug，就没必要推到远程了，除非老板要看看你每周到底修复了几个bug；
    feature分支是否推到远程，取决于你是否和你的小伙伴合作在上面开发。
    
	查看远程库信息，使用git remote -v；
	本地新建的分支如果不推送到远程，对其他人就是不可见的；
	从本地推送分支，使用git push origin branch-name，如果推送失败，先用git pull抓取远程的新提交；
	在本地创建和远程分支对应的分支，使用git checkout -b branch-name origin/branch-name，本地和远程分支的名称最好一致；
	建立本地分支和远程分支的关联，使用git branch --set-upstream branch-name origin/branch-name；
	从远程抓取分支，使用git pull，如果有冲突，要先处理冲突。

## 创建标签
    命令git tag name用于新建一个标签，默认为HEAD，也可以指定一个commit id；
    -a tagname -m "blablabla..."可以指定标签信息；
    -s tagname -m "blablabla..."可以用PGP签名标签；
    命令git tag可以查看所有标签；
    操作标签
    命令git push origin tagname可以推送一个本地标签；
    命令git push origin --tags可以推送全部未推送过的本地标签；
    命令git tag -d tagname可以删除一个本地标签；
    命令git push origin :refs/tags/tagname可以删除一个远程标签。

## 新增CREATE 
- Clone 
- git init 新建
## 本地操作
1.git status 查看当前工作目录的变更情况
2.git diff   工作区和暂存区比较
3.git add .   添加当前的
4.git add -p <file>

    …or create a new repository on the command line
    echo # note >> README.md
    git init
    git add README.md
    git commit -m "first commit"
    git remote add origin git@github.com:hepanming007/note.git
    git push -u origin master
    …or push an existing repository from the command line


    git remote add origin git@github.com:hepanming007/note.git
    git push -u origin master
    …or import code from another repository
    
You can initialize this repository with code from a Subversion, Mercurial, or TFS project.