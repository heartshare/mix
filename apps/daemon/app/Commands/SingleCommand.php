<?php

namespace Apps\Daemon\Commands;

use Mix\Database\Persistent\PDOConnection;
use Mix\Console\ExitCode;
use Mix\Facades\Error;
use Mix\Facades\Input;

/**
 * 单进程范例
 * @author 刘健 <coder.liu@qq.com>
 */
class SingleCommand extends BaseCommand
{

    // 初始化事件
    public function onInitialize()
    {
        parent::onInitialize(); // TODO: Change the autogenerated stub
        // 获取程序名称
        $this->programName = Input::getCommandName();
        // 设置pidfile
        $this->pidFile = "/var/run/{$this->programName}.pid";
    }

    // 启动
    public function actionStart()
    {
        // 预处理
        if (!parent::actionStart()) {
            return ExitCode::UNSPECIFIED_ERROR;
        }
        // 开始工作
        $this->startWork();
        // 返回退出码
        return ExitCode::OK;
    }

    // 开始工作
    public function startWork()
    {
        try {
            $this->work();
        } catch (\Throwable $e) {
            // 处理异常
            Error::handleException($e);
            // 休息一会，避免 CPU 出现 100%
            sleep(1);
            // 重建流程
            $this->startWork();
        }
    }

    // 执行工作
    public function work()
    {
        // 使用长连接客户端，这样会自动帮你维护连接不断线
        $pdo = PDOConnection::newInstanceByConfig();
        // 循环执行任务
        while (true) {
            // 执行业务代码
            // ...
        }
    }

}