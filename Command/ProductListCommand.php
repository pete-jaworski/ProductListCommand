<?php
namespace PiotrJaworski\ProductListCommand\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductListCommand extends Command
{
    
    const NAME_ARGUMENT = 'search';
    
    protected $productCollectionFactory;
    
    public function __construct(CollectionFactory $productCollectionFactory) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct();
    }    
    
    
    
    
    protected function configure()
    {
        $this->setName('piotrjaworski:product_list')
                ->setDescription('List all products')
                ->setDefinition([
                new InputArgument(
                    self::NAME_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'search'
                ),
            ]);
    }

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {         
        if (is_null($input->getArgument(self::NAME_ARGUMENT))) {
        
            $collection = $this->productCollectionFactory->create()
                    ->addAttributeToSelect('*')
                    ->load();

        } else {

            $collection = $this->productCollectionFactory->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('name', array(
                        array('like' => '%'.$input->getArgument(self::NAME_ARGUMENT).'%'), 
                        array('like' => '%'.$input->getArgument(self::NAME_ARGUMENT)), 
                        array('like' => $input->getArgument(self::NAME_ARGUMENT).'%')
                    ))
                    ->load();
                
                
        }
        
        

        $products = '';
        
        foreach ($collection as $product){
             $products .= $product->getName(). PHP_EOL;
        }  
        
        
        if ($products == ''){
            $products = "Nothing found";
        }
        
        $output->writeln($products);
    }
 

}
