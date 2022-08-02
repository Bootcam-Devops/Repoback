<?php
namespace repoback\PageBuilderBlockTheme\Setup\Patch\Data;

use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Api\GetBlockByIdentifierInterface;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AmazonClothing implements DataPatchInterface
{
    protected BlockRepository $blockRepository;
    protected BlockInterfaceFactory $block;
    protected ModuleDataSetupInterface $moduleDataSetup;
    protected GetBlockByIdentifierInterface $blockByIdentifier;
    /**
     * BranchesPanama constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockRepository $blockRepository
     * @param BlockInterfaceFactory $block
     * @param GetBlockByIdentifierInterface $blockByIdentifier
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepository $blockRepository,
        BlockInterfaceFactory $block,
        GetBlockByIdentifierInterface $blockByIdentifier
    ) {
        $this->blockRepository      = $blockRepository;
        $this->block                = $block;
        $this->moduleDataSetup      = $moduleDataSetup;
        $this->blockByIdentifier    = $blockByIdentifier;
    }
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $block_data_head = [
            'title' => 'home_page_clothing',
            'identifier' => 'home-page',
            'is_active' => 1,
            'content' => file_get_contents(__DIR__ . '/html/Amazon_clothing.html')
        ];
        $block_head = $this->block->create();
        $block_head->addData($block_data_head);
        $block_head->setStores([0]);
        $this->blockRepository->save($block_head);
        $this->moduleDataSetup->getConnection()->endSetup();
    }
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
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}