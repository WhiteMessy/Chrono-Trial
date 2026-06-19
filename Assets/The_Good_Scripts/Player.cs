using System.Collections;
using UnityEngine;
using UnityEngine.UI;

public class Player : MonoBehaviour
{

    public void SaveCoins()
    {
    if (supabase != null)
    {
        supabase.UpdateCoins(playerId, coins);
        Debug.Log("Coins saved: " + coins);
    }
    }
    public SupabaseManager supabase;
    public int playerId = 0;
    public int coins;
    public float bestTime;

    public int health = 100;
    public float moveSpeed = 5f;
    public float jumpForce = 10f;

    public Transform groundCheck;
    public float groundCheckRadius = 0.2f;
    public LayerMask groundLayer;

    private Rigidbody2D rb;
    private bool isGrounded;
    private Animator animator;

    public SpriteRenderer spriteRenderer;

    [Header("Game Over")]
    public GameOverManager gameOverManager;
    private bool isDead = false;

    void Start()
    {
    rb = GetComponent<Rigidbody2D>();
    animator = GetComponent<Animator>();
    spriteRenderer = GetComponent<SpriteRenderer>();

    string username = PlayerPrefs.GetString("Username");

    Debug.Log("Username from PlayerPrefs: " + username);

    if (supabase != null)
    {
        supabase.LoadCoins(username);
    }
    }

    void Update()
    {
        if (isDead) return;

        float moveInput = Input.GetAxis("Horizontal");
        rb.linearVelocity = new Vector2(moveInput * moveSpeed, rb.linearVelocity.y);

        if (Input.GetKeyDown(KeyCode.Space) && isGrounded)
        {
            rb.linearVelocity = new Vector2(rb.linearVelocity.x, jumpForce);
        }

        SetAnimation(moveInput);

        if (transform.position.y < -10)
        {
            Die();
        }
    }

    public void SetCoins(int amount)
        {
    coins = amount;

    GameObject.FindWithTag("CoinText")
        .GetComponent<TMPro.TextMeshProUGUI>()
        .text = coins.ToString();

    Debug.Log("Loaded coins: " + coins);
    }

    private void FixedUpdate()
    {
        if (isDead) return;

        isGrounded = Physics2D.OverlapCircle(
            groundCheck.position,
            groundCheckRadius,
            groundLayer
        );
    }

    private void SetAnimation(float moveInput)
    {
        if (isGrounded)
        {
            if (moveInput == 0)
            {
                animator.Play("Player_Idle");
            }
            else
            {
                animator.Play("Player_Run");
            }
        }
    }

    private void OnCollisionEnter2D(Collision2D collision)
    {
        if (isDead) return;

        if (collision.gameObject.CompareTag("Damage"))
        {
            health -= 100;
            rb.linearVelocity = new Vector2(rb.linearVelocity.x, jumpForce);
            StartCoroutine(BlinkRed());

            if (health <= 0)
            {
                Die();
            }
        }
    }

    private IEnumerator BlinkRed()
    {
        spriteRenderer.color = Color.red;
        yield return new WaitForSeconds(0.1f);
        spriteRenderer.color = Color.white;
    }

    public void Die()
    {
        if (isDead) return;

        isDead = true;

        if (gameOverManager != null)
        {
            gameOverManager.GameOver();
        }
        else
        {
            Debug.LogError("GameOverManager is not assigned on the Player!");
        }
    }
}