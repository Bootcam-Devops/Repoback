<?php

namespace repoback\PageBuilderBlockTheme\Setup\Patch\Data;

class AmazonTheme implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * Constructor BlockMenuNavigation
     */

    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Cms\Model\BlockRepository $blockRepository,
        \Magento\Cms\Api\Data\BlockInterfaceFactory $block,
        \Magento\Cms\Api\GetBlockByIdentifierInterface $blockByIdentifier
    ) {
        $this->blockRepository = $blockRepository;
        $this->block = $block;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockByIdentifier = $blockByIdentifier;
    }
    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $block_data_menu = [
            'title' => 'Amazon-productos',
            'identifier' => 'Amazon-productos',
            'is_active' => 1,
            'content' => file_get_contents(__DIR__ . '/html/Amazon_productos.html'),
        ];

        $this->makeBackup($block_data_menu);
        $block_menu = $this->block->create();
        $block_menu->addData($block_data_menu);
        $block_menu->setStores([0]);
        $this->blockRepository->save($block_menu);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function makeBackup($data)
    {
        $block = $this->block->create()->load($data['identifier'], 'identifier');
        if ($block->getId() > 0) {
            $backup = $this->block->create()->load($data['identifier'] . '-backup', 'identifier');
            if ($backup->getId() > 0) {
                $backup->delete();
            }
            $block->setIdentifier($data['identifier'] . '-backup')->setActive(0)->save();
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
    /**
     * Revert patch
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //code
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}


