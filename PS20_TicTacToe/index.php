<?php
session_start();

// Debug information
if (isset($_POST)) {
    error_log("POST data received: " . print_r($_POST, true));
}

class TicTacToe {
    private $board;
    private $currentPlayer;
    private $gameOver;
    private $winner;
    
    public function __construct() {
        if (!isset($_SESSION['game'])) {
            $this->resetGame();
        } else {
            $game = $_SESSION['game'];
            $this->board = $game['board'];
            $this->currentPlayer = $game['currentPlayer'];
            $this->gameOver = $game['gameOver'];
            $this->winner = $game['winner'];
        }
    }
    
    public function resetGame() {
        $this->board = array_fill(0, 9, '');
        $this->currentPlayer = 'X';
        $this->gameOver = false;
        $this->winner = null;
        $this->saveGame();
    }
    
    public function makeMove($position) {
        if ($this->gameOver || $this->board[$position] !== '') {
            return false;
        }
        
        $this->board[$position] = $this->currentPlayer;
        
        if ($this->checkWinner()) {
            $this->gameOver = true;
            $this->winner = $this->currentPlayer;
        } elseif ($this->isBoardFull()) {
            $this->gameOver = true;
            $this->winner = 'tie';
        } else {
            $this->currentPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';
        }
        
        $this->saveGame();
        return true;
    }
    
    private function checkWinner() {
        $winningCombinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // columns
            [0, 4, 8], [2, 4, 6] // diagonals
        ];
        
        foreach ($winningCombinations as $combo) {
            if ($this->board[$combo[0]] !== '' &&
                $this->board[$combo[0]] === $this->board[$combo[1]] &&
                $this->board[$combo[1]] === $this->board[$combo[2]]) {
                return true;
            }
        }
        
        return false;
    }
    
    private function isBoardFull() {
        return !in_array('', $this->board);
    }
    
    private function saveGame() {
        $_SESSION['game'] = [
            'board' => $this->board,
            'currentPlayer' => $this->currentPlayer,
            'gameOver' => $this->gameOver,
            'winner' => $this->winner
        ];
    }
    
    public function getBoard() {
        return $this->board;
    }
    
    public function getCurrentPlayer() {
        return $this->currentPlayer;
    }
    
    public function isGameOver() {
        return $this->gameOver;
    }
    
    public function getWinner() {
        return $this->winner;
    }
}

// Handle game actions
$game = new TicTacToe();

// Debug: Show current game state
$debug_info = "Current Player: " . $game->getCurrentPlayer() . " | Game Over: " . ($game->isGameOver() ? 'Yes' : 'No');

// Add more detailed debugging
if (!empty($_POST)) {
    $debug_info .= " | POST received: " . json_encode($_POST);
}

if (isset($_POST['move']) && isset($_POST['position'])) {
    $position = intval($_POST['position']);
    $success = $game->makeMove($position);
    $debug_info .= " | Move at $position: " . ($success ? 'Success' : 'Failed');
    // Remove redirect temporarily for debugging
    // header('Location: index.php');
    // exit;
}

if (isset($_POST['reset'])) {
    $game->resetGame();
    $debug_info .= " | Game Reset";
    // Remove redirect temporarily for debugging
    // header('Location: index.php');
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Tic-Tac-Toe</h1>
            <!-- Debug info -->
            <div style="font-size: 12px; color: #666; margin-bottom: 10px;">
                Debug: <?php echo $debug_info; ?>
            </div>
            <div class="game-info">
                <?php if ($game->isGameOver()): ?>
                    <?php if ($game->getWinner() === 'tie'): ?>
                        <div class="status tie">It's a Tie!</div>
                    <?php else: ?>
                        <div class="status winner">Player <?php echo $game->getWinner(); ?> Wins!</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="status current">Current Player: <span class="player-<?php echo strtolower($game->getCurrentPlayer()); ?>"><?php echo $game->getCurrentPlayer(); ?></span></div>
                <?php endif; ?>
            </div>
        </header>

        <main>
            <div class="game-board">
                <?php for ($i = 0; $i < 9; $i++): ?>
                    <div class="cell <?php echo $game->getBoard()[$i] ? 'filled' : ''; ?>">
                        <?php if ($game->getBoard()[$i] === ''): ?>
                            <?php if (!$game->isGameOver()): ?>
                                <form method="POST" style="display: block; width: 100%; height: 100%;">
                                    <input type="hidden" name="position" value="<?php echo $i; ?>">
                                    <input type="hidden" name="move" value="1">
                                    <button type="submit" class="cell-button">
                                        <span class="cell-hover-text"><?php echo $game->getCurrentPlayer(); ?></span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="player-mark player-<?php echo strtolower($game->getBoard()[$i]); ?>">
                                <?php echo $game->getBoard()[$i]; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="controls">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="reset" value="1">
                    <button type="submit" class="reset-button">New Game</button>
                </form>
            </div>
        </main>

        <footer>
            <p>Built with PHP • Professional Tic-Tac-Toe</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>