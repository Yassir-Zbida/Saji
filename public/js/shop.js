document.addEventListener("DOMContentLoaded", () => {
  // Animation for product cards with intersection observer
  initProductCardAnimations()

  // Initialize view toggle (grid/list) if present on the page
  if (document.querySelector(".view-toggle-btn")) {
    initViewToggle()
  }

  // Initialize sorting if present on the page
  if (document.getElementById("sort-select")) {
    initSorting()
  }

  // Initialize filter modal if present on the page
  if (document.getElementById("filterModal")) {
    initFilterModal()
  }

  // Initialize load more functionality if present on the page
  if (document.getElementById("load-more-btn")) {
    initLoadMore()
  }

  // Initialize wishlist functionality
  initWishlistFunctionality()

  // Initialize "Add to Cart" functionality
  initAddToCart()

  // Fetch cart count on page load
  fetchCartCount()

  /**
   * Initialize animations for product cards using Intersection Observer
   */
  function initProductCardAnimations() {
    const animatedElements = document.querySelectorAll(".product-card")

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.animationPlayState = "running"
            observer.unobserve(entry.target)
          }
        })
      },
      {
        threshold: 0.1,
      },
    )

    animatedElements.forEach((el) => {
      el.style.animationPlayState = "paused"
      observer.observe(el)
    })
  }

  // Create a single toast container to avoid duplicates
  let toastContainer = null

  /**
   * Show toast notification
   */
  function showToast(message, type = "info") {
    // Create toast container if it doesn't exist
    if (!toastContainer) {
      toastContainer = document.createElement("div")
      toastContainer.className = "toast-container fixed bottom-4 right-4 z-50"
      document.body.appendChild(toastContainer)
    }

    // Check if a toast with the same message already exists
    const existingToasts = toastContainer.querySelectorAll(".toast")
    for (let i = 0; i < existingToasts.length; i++) {
      if (existingToasts[i].querySelector("span").textContent === message) {
        return // Don't show duplicate toast
      }
    }

    const toast = document.createElement("div")
    toast.className =
      "toast p-4 mb-3 rounded-lg shadow-lg flex items-center justify-between transition-all transform translate-y-2 opacity-0"

    switch (type) {
      case "success":
        toast.classList.add("bg-green-500", "text-white")
        break
      case "error":
        toast.classList.add("bg-red-500", "text-white")
        break
      case "info":
        toast.classList.add("bg-blue-500", "text-white")
        break
      default:
        toast.classList.add("bg-gray-800", "text-white")
    }

    toast.innerHTML = `
      <span>${message}</span>
      <button class="ml-4 focus:outline-none" onclick="this.parentElement.remove()">
          <i class="ri-close-line"></i>
      </button>
    `

    toastContainer.appendChild(toast)

    // Animate toast in
    setTimeout(() => {
      toast.classList.remove("translate-y-2", "opacity-0")
      toast.classList.add("translate-y-0", "opacity-100")
    }, 10)

    // Animate toast out after 4 seconds
    setTimeout(() => {
      toast.classList.add("translate-y-2", "opacity-0")
      setTimeout(() => {
        toast.remove()
      }, 300)
    }, 4000)
  }

  /**
   * Initialize view toggle (grid/list)
   */
  function initViewToggle() {
    const viewToggleButtons = document.querySelectorAll(".view-toggle-btn")
    const productsContainer = document.getElementById("products-container")

    if (viewToggleButtons.length && productsContainer) {
      viewToggleButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
          // Remove active class from all buttons
          viewToggleButtons.forEach((b) => b.classList.remove("active"))

          // Add active class to clicked button
          this.classList.add("active")

          // Get view type
          const viewType = this.getAttribute("data-view")

          // Update products container class
          if (viewType === "list") {
            productsContainer.classList.remove("grid-cols-2", "sm:grid-cols-3", "lg:grid-cols-4", "xl:grid-cols-5")
            productsContainer.classList.add("grid-cols-1")

            // Add list view specific classes to product cards
            document.querySelectorAll(".product-card").forEach((card) => {
              card.classList.add("md:flex-row")
              
              // Make image container smaller in list view
              const imgContainer = card.querySelector(".product-image-container")
              if (imgContainer) {
                imgContainer.classList.add("md:w-1/4", "md:aspect-auto", "md:h-auto")
              }
              
              // Adjust content container in list view
              const contentContainer = card.querySelector(".product-content")
              if (contentContainer) {
                contentContainer.classList.add("md:w-3/4", "md:flex", "md:flex-col", "md:justify-between")
              }
              
              // Show description in list view
              const description = card.querySelector(".product-description")
              if (description) {
                description.classList.remove("hidden")
              }
              
              // Make add to cart button not full width in list view
              const addToCartBtn = card.querySelector(".add-to-cart-btn")
              if (addToCartBtn) {
                addToCartBtn.classList.remove("w-full")
                addToCartBtn.classList.add("md:w-auto", "md:px-4")
              }
            })
          } else {
            productsContainer.classList.remove("grid-cols-1")
            productsContainer.classList.add("grid-cols-2", "sm:grid-cols-3", "lg:grid-cols-4", "xl:grid-cols-5")

            // Remove list view specific classes from product cards
            document.querySelectorAll(".product-card").forEach((card) => {
              card.classList.remove("md:flex-row")
              
              // Reset image container
              const imgContainer = card.querySelector(".product-image-container")
              if (imgContainer) {
                imgContainer.classList.remove("md:w-1/4", "md:aspect-auto", "md:h-auto")
              }
              
              // Reset content container
              const contentContainer = card.querySelector(".product-content")
              if (contentContainer) {
                contentContainer.classList.remove("md:w-3/4", "md:flex", "md:flex-col", "md:justify-between")
              }
              
              // Hide description in grid view
              const description = card.querySelector(".product-description")
              if (description) {
                description.classList.add("hidden")
              }
              
              // Reset add to cart button to full width
              const addToCartBtn = card.querySelector(".add-to-cart-btn")
              if (addToCartBtn) {
                addToCartBtn.classList.add("w-full")
                addToCartBtn.classList.remove("md:w-auto", "md:px-4")
              }
            })
          }

          // Save preference in localStorage
          localStorage.setItem("shop-view-preference", viewType)

          // Re-initialize add to cart buttons after view change
          setTimeout(initAddToCart, 300)
        })
      })

      // Load saved preference on page load
      const savedViewPreference = localStorage.getItem("shop-view-preference")
      if (savedViewPreference) {
        const button = document.querySelector(`.view-toggle-btn[data-view="${savedViewPreference}"]`)
        if (button) {
          button.click()
        }
      }
    }
  }

  /**
   * Initialize sorting functionality with AJAX
   */
  function initSorting() {
    const sortSelect = document.getElementById("sort-select")
    const productsContainer = document.getElementById("products-container")
    const loadMoreContainer = document.getElementById("load-more-container")

    if (sortSelect && productsContainer) {
      // First, remove any existing event listeners by cloning and replacing
      const newSortSelect = sortSelect.cloneNode(true)
      sortSelect.parentNode.replaceChild(newSortSelect, sortSelect)

      // Now add our event listener to the fresh element
      newSortSelect.addEventListener("change", function (e) {
        e.preventDefault() // Prevent form submission if within a form

        // Show loading state
        productsContainer.classList.add("opacity-50")
        productsContainer.style.pointerEvents = "none"

        // Create loading overlay
        const loadingOverlay = document.createElement("div")
        loadingOverlay.className = "fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center"
        loadingOverlay.innerHTML = `
          <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
              <i class="ri-loader-4-line text-2xl animate-spin mr-3"></i>
              <span>Sorting products...</span>
          </div>
        `
        document.body.appendChild(loadingOverlay)

        // Get current URL and add sort parameter
        const currentUrl = new URL(window.location.href)
        currentUrl.searchParams.set("sort", this.value)
        currentUrl.searchParams.set("ajax", "1")

        // Reset to page 1 when sorting changes
        currentUrl.searchParams.delete("page")

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        // Fetch sorted products
        fetch(currentUrl.toString(), {
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken || '',
            "Accept": "application/json"
          },
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`Server responded with status: ${response.status}`)
            }
            return response.json()
          })
          .then((data) => {
            // Update browser URL without page reload to maintain state
            const displayUrl = new URL(window.location.href)
            displayUrl.searchParams.set("sort", this.value)
            displayUrl.searchParams.delete("page")
            window.history.pushState({ sort: this.value }, "", displayUrl.toString())

            // Handle the response data
            if (data.html) {
              // Clear existing products
              productsContainer.innerHTML = ""

              // Create a temporary container to parse the HTML
              const temp = document.createElement("div")
              temp.innerHTML = data.html

              // Get all product cards from the response
              const newProducts = temp.querySelectorAll(".product-card")

              if (newProducts.length > 0) {
                // Add new products to container
                newProducts.forEach((product) => {
                  productsContainer.appendChild(product)
                })

                // Re-initialize product card animations
                initProductCardAnimations()

                // Re-initialize wishlist functionality
                initWishlistFunctionality()

                // Re-initialize add to cart buttons
                initAddToCart()
              } else {
                // No products found
                productsContainer.innerHTML = `
                  <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-lg border border-gray-200">
                      <div class="text-gray-400 mb-4">
                          <i class="ri-shopping-bag-3-line text-6xl"></i>
                      </div>
                      <h2 class="text-xl font-medium text-gray-900 mb-2">No products found</h2>
                      <p class="text-gray-500 mb-6 text-center max-w-md">
                          Sorry, we couldn't find any products matching your criteria. 
                          Try adjusting your filters or browse our other categories.
                      </p>
                  </div>
                `
              }

              // Update load more button visibility
              if (loadMoreContainer) {
                if (data.has_more_pages === false) {
                  loadMoreContainer.classList.add("hidden")
                } else {
                  loadMoreContainer.classList.remove("hidden")
                }
              }
            }
          })
          .catch((error) => {
            console.error("Error sorting products:", error)
            showToast("Error sorting products. Please try again.", "error")
          })
          .finally(() => {
            // Remove loading state
            productsContainer.classList.remove("opacity-50")
            productsContainer.style.pointerEvents = "auto"

            // Remove loading overlay
            if (document.body.contains(loadingOverlay)) {
              document.body.removeChild(loadingOverlay)
            }
          })
      })
    }
  }

  /**
   * Initialize filter modal
   */
  function initFilterModal() {
    const openFiltersBtn = document.getElementById("openFilters")
    const closeFiltersBtn = document.getElementById("closeFilters")
    const overlay = document.getElementById("overlay")
    const filterModal = document.getElementById("filterModal")
    const filterSidebar = document.getElementById("filterSidebar")

    if (openFiltersBtn && filterModal) {
      openFiltersBtn.addEventListener("click", () => {
        filterModal.classList.remove("hidden")
        setTimeout(() => {
          filterSidebar.classList.remove("-translate-x-full")
        }, 10)
      })
    }

    if (closeFiltersBtn && filterModal) {
      const closeFilter = () => {
        filterSidebar.classList.add("-translate-x-full")
        setTimeout(() => {
          filterModal.classList.add("hidden")
        }, 300)
      }

      closeFiltersBtn.addEventListener("click", closeFilter)
      if (overlay) {
        overlay.addEventListener("click", closeFilter)
      }
    }

    // Price range slider functionality
    initPriceRangeSlider()

    // Apply filter buttons
    initFilterButtons()

    // Clear filters functionality
    initClearFilters()
  }

  /**
   * Initialize price range slider
   */
  function initPriceRangeSlider() {
    const priceMin = document.getElementById("price-min")
    const priceMax = document.getElementById("price-max")
    const priceMinValue = document.getElementById("price-min-value")
    const priceMaxValue = document.getElementById("price-max-value")
    const priceRangeProgress = document.getElementById("price-range-progress")

    if (priceMin && priceMinValue) {
      priceMin.addEventListener("input", function () {
        priceMinValue.textContent = this.value
        updatePriceRangeProgress()
      })
    }

    if (priceMax && priceMaxValue) {
      priceMax.addEventListener("input", function () {
        priceMaxValue.textContent = this.value
        updatePriceRangeProgress()
      })
    }

    function updatePriceRangeProgress() {
      if (priceRangeProgress && priceMin && priceMax) {
        const min = Number.parseInt(priceMin.value)
        const max = Number.parseInt(priceMax.value)
        const minPos = (min / Number.parseInt(priceMin.max)) * 100
        const maxPos = (max / Number.parseInt(priceMax.max)) * 100

        priceRangeProgress.style.left = minPos + "%"
        priceRangeProgress.style.width = maxPos - minPos + "%"
      }
    }

    // Initialize on page load
    if (priceMin && priceMax) {
      updatePriceRangeProgress()
    }
  }

  /**
   * Initialize filter buttons
   */
  function initFilterButtons() {
    const applyPriceFilterBtn = document.getElementById("apply-price-filter")
    const applyAllFiltersBtn = document.getElementById("apply-all-filters")

    if (applyPriceFilterBtn) {
      applyPriceFilterBtn.addEventListener("click", applyFiltersWithAjax)
    }

    if (applyAllFiltersBtn) {
      applyAllFiltersBtn.addEventListener("click", applyFiltersWithAjax)
    }

    function applyFiltersWithAjax() {
      const productsContainer = document.getElementById("products-container")
      const loadMoreContainer = document.getElementById("load-more-container")

      if (!productsContainer) return

      // Show loading state
      productsContainer.classList.add("opacity-50")
      productsContainer.style.pointerEvents = "none"

      // Create loading overlay
      const loadingOverlay = document.createElement("div")
      loadingOverlay.className = "fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center"
      loadingOverlay.innerHTML = `
        <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
            <i class="ri-loader-4-line text-2xl animate-spin mr-3"></i>
            <span>Filtering products...</span>
        </div>
      `
      document.body.appendChild(loadingOverlay)

      // Close filter modal
      const filterModal = document.getElementById("filterModal")
      const filterSidebar = document.getElementById("filterSidebar")
      if (filterModal && filterSidebar) {
        filterSidebar.classList.add("-translate-x-full")
        setTimeout(() => {
          filterModal.classList.add("hidden")
        }, 300)
      }

      // Build URL with filter parameters
      const currentUrl = new URL(window.location.href)

      // Get price values
      const priceMin = document.getElementById("price-min")
      const priceMax = document.getElementById("price-max")

      if (priceMin && priceMax) {
        currentUrl.searchParams.set("min_price", priceMin.value)
        currentUrl.searchParams.set("max_price", priceMax.value)
      }

      // Get category values
      const categoryCheckboxes = document.querySelectorAll(".category-filter:checked")
      currentUrl.searchParams.delete("category[]")
      categoryCheckboxes.forEach((checkbox) => {
        currentUrl.searchParams.append("category[]", checkbox.value)
      })

      // Get color values
      const colorCheckboxes = document.querySelectorAll(".color-filter:checked")
      currentUrl.searchParams.delete("color[]")
      colorCheckboxes.forEach((checkbox) => {
        currentUrl.searchParams.append("color[]", checkbox.value)
      })

      // Reset page to 1
      currentUrl.searchParams.delete("page")

      // Add ajax parameter
      currentUrl.searchParams.set("ajax", "1")

      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

      // Fetch filtered products
      fetch(currentUrl.toString(), {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken || '',
          "Accept": "application/json"
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          // Update browser URL without page reload to maintain state
          const displayUrl = new URL(currentUrl)
          displayUrl.searchParams.delete("ajax")
          window.history.pushState({ filters: true }, "", displayUrl.toString())

          // Handle the response data
          if (data.html) {
            // Clear existing products
            productsContainer.innerHTML = ""

            // Create a temporary container to parse the HTML
            const temp = document.createElement("div")
            temp.innerHTML = data.html

            // Get all product cards from the response
            const newProducts = temp.querySelectorAll(".product-card")

            if (newProducts.length > 0) {
              // Add new products to container
              newProducts.forEach((product) => {
                productsContainer.appendChild(product)
              })

              // Re-initialize product card animations
              initProductCardAnimations()

              // Re-initialize wishlist functionality
              initWishlistFunctionality()

              // Re-initialize add to cart buttons
              initAddToCart()
            } else {
              // No products found
              productsContainer.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-lg border border-gray-200">
                    <div class="text-gray-400 mb-4">
                        <i class="ri-shopping-bag-3-line text-6xl"></i>
                    </div>
                    <h2 class="text-xl font-medium text-gray-900 mb-2">No products found</h2>
                    <p class="text-gray-500 mb-6 text-center max-w-md">
                        Sorry, we couldn't find any products matching your criteria. 
                        Try adjusting your filters or browse our other categories.
                    </p>
                </div>
              `
            }

            // Update load more button visibility
            if (loadMoreContainer) {
              if (data.has_more_pages === false) {
                loadMoreContainer.classList.add("hidden")
              } else {
                loadMoreContainer.classList.remove("hidden")
              }
            }
          }
        })
        .catch((error) => {
          console.error("Error filtering products:", error)
          showToast("Error filtering products. Please try again.", "error")
        })
        .finally(() => {
          // Remove loading state
          productsContainer.classList.remove("opacity-50")
          productsContainer.style.pointerEvents = "auto"

          // Remove loading overlay
          if (document.body.contains(loadingOverlay)) {
            document.body.removeChild(loadingOverlay)
          }
        })
    }

    // Handle individual filter tag removals
    const removeFilterButtons = document.querySelectorAll("[data-remove]")
    removeFilterButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        const currentUrl = new URL(window.location.href)
        const filterType = this.getAttribute("data-remove")
        const filterValue = this.getAttribute("data-value")

        if (filterType === "price") {
          currentUrl.searchParams.delete("min_price")
          currentUrl.searchParams.delete("max_price")
        } else if (filterValue) {
          // For array parameters like category[] or color[]
          const values = currentUrl.searchParams.getAll(filterType + "[]")
          currentUrl.searchParams.delete(filterType + "[]")

          values.forEach((val) => {
            if (val !== filterValue) {
              currentUrl.searchParams.append(filterType + "[]", val)
            }
          })
        } else {
          currentUrl.searchParams.delete(filterType)
        }

        window.location.href = currentUrl.toString()
      })
    })
  }

  /**
   * Initialize clear filters functionality
   */
  function initClearFilters() {
    const clearAllFiltersBtn = document.getElementById("clear-all-filters")

    if (clearAllFiltersBtn) {
      clearAllFiltersBtn.addEventListener("click", () => {
        const currentUrl = new URL(window.location.href)

        // Keep only sort and page parameters
        const sort = currentUrl.searchParams.get("sort")
        const page = currentUrl.searchParams.get("page")

        // Clear all parameters
        currentUrl.search = ""

        // Add back sort and page if they existed
        if (sort) currentUrl.searchParams.set("sort", sort)
        if (page) currentUrl.searchParams.set("page", page)

        window.location.href = currentUrl.toString()
      })
    }
  }

  /**
   * Initialize load more functionality
   */
  function initLoadMore() {
    const loadMoreBtn = document.getElementById("load-more-btn")
    const loadMoreContainer = document.getElementById("load-more-container")
    const productsContainer = document.getElementById("products-container")

    if (loadMoreBtn && productsContainer) {
      // Get current page from URL or default to 1
      let currentPage = 1
      const urlParams = new URLSearchParams(window.location.search)
      if (urlParams.has("page")) {
        currentPage = Number.parseInt(urlParams.get("page"))
      }

      loadMoreBtn.addEventListener("click", function () {
        // Show loading indicator
        const loadingIcon = this.querySelector(".ri-loader-4-line")
        if (loadingIcon) {
          loadingIcon.classList.remove("hidden")
        }
        this.disabled = true

        // Get next page
        currentPage++

        // Create URL for AJAX request
        const currentUrl = new URL(window.location.href)
        currentUrl.searchParams.set("page", currentPage)
        currentUrl.searchParams.set("ajax", "1") // Add ajax parameter

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        // Fetch next page
        fetch(currentUrl.toString(), {
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken || '',
            "Accept": "application/json"
          },
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`Server responded with status: ${response.status}`)
            }
            return response.json()
          })
          .then((data) => {
            // Handle the response data
            if (data.html) {
              // Create a temporary container to parse the HTML
              const temp = document.createElement("div")
              temp.innerHTML = data.html

              // Append new product cards to container
              const newProducts = temp.querySelectorAll(".product-card")

              if (newProducts.length > 0) {
                newProducts.forEach((product) => {
                  productsContainer.appendChild(product)
                })

                // Re-initialize product card animations for new cards
                initProductCardAnimations()

                // Re-initialize wishlist functionality for new cards
                initWishlistFunctionality()

                // Re-initialize add to cart buttons
                initAddToCart()
              } else {
                // No more products
                loadMoreContainer.classList.add("hidden")
                showToast("No more products to load", "info")
              }

              // Hide load more button if no more pages or explicitly told
              if (data.has_more_pages === false) {
                loadMoreContainer.classList.add("hidden")
              }
            } else {
              // If there's no HTML in the response
              loadMoreContainer.classList.add("hidden")
              showToast("No more products to load", "info")
            }
          })
          .catch((error) => {
            console.error("Error loading more products:", error)
            showToast("Error loading more products. Please try again.", "error")
          })
          .finally(() => {
            // Hide loading indicator
            if (loadingIcon) {
              loadingIcon.classList.add("hidden")
            }
            this.disabled = false
          })
      })
    }
  }

  /**
   * Initialize wishlist functionality
   */
  function initWishlistFunctionality() {
    // Clone and replace all wishlist buttons to remove old event listeners
    // Handle both button classes: product-wishlist-btn (home) and add-to-wishlist (shop)
    const allWishlistButtons = document.querySelectorAll(".product-wishlist-btn, .add-to-wishlist")
    allWishlistButtons.forEach((btn) => {
      const newBtn = btn.cloneNode(true)
      btn.parentNode.replaceChild(newBtn, btn)
    })

    // Load wishlist status and set up buttons
    loadWishlistStatus()
    setupWishlistButtons()
    updateWishlistCount()
  }

  /**
   * Load wishlist status from server
   */
  function loadWishlistStatus() {
    if (document.body.classList.contains("logged-in")) {
      const productCards = document.querySelectorAll(".product-card")
      if (productCards.length === 0) return

      const productIds = []
      productCards.forEach((card) => {
        const productId = card.getAttribute("data-product-id")
        if (productId) productIds.push(productId)
      })

      if (productIds.length === 0) return

      const csrfToken = document.querySelector('meta[name="csrf-token"]')
      if (!csrfToken) {
        console.error("CSRF token not found")
        return
      }

      fetch("/wishlist/check-products", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
          "X-Requested-With": "XMLHttpRequest",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          product_ids: productIds,
        }),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          if (data.status === "success") {
            productCards.forEach((card) => {
              const productId = card.getAttribute("data-product-id")
              if (productId && data.in_wishlist.includes(Number.parseInt(productId))) {
                // Check for both home and shop wishlist buttons
                const wishlistBtn =
                  card.querySelector(".product-wishlist-btn") || card.querySelector(".add-to-wishlist")

                if (wishlistBtn) {
                  wishlistBtn.classList.add("active")
                  // Don't add red background, just change the icon

                  // Handle different icon classes in different views
                  const heartIcon =
                    wishlistBtn.querySelector(".wishlist-icon") || wishlistBtn.querySelector(".ri-heart-line")

                  if (heartIcon) {
                    if (heartIcon.classList.contains("ri-heart-line")) {
                      heartIcon.classList.remove("ri-heart-line")
                      heartIcon.classList.add("ri-heart-fill")
                      heartIcon.classList.add("text-red-500") // Make icon red
                    } else if (heartIcon.classList.contains("wishlist-icon")) {
                      heartIcon.classList.remove("ri-heart-line")
                      heartIcon.classList.add("ri-heart-fill")
                      heartIcon.classList.add("text-red-500") // Make icon red
                    }
                  }
                }
              }
            })
          }
        })
        .catch((error) => console.error("Error checking wishlist status:", error))
    }
  }

  /**
   * Set up wishlist button event listeners
   */
  function setupWishlistButtons() {
    // Select both button types: home page and shop page
    const wishlistButtons = document.querySelectorAll(".product-wishlist-btn, .add-to-wishlist")

    wishlistButtons.forEach((btn) => {
      btn.addEventListener("click", function (e) {
        e.preventDefault()
        e.stopPropagation()

        // Redirect to login if user is not logged in
        if (!document.body.classList.contains("logged-in")) {
          sessionStorage.setItem("redirectAfterLogin", window.location.href)
          window.location.href = "/login"
          return
        }

        const productCard = this.closest(".product-card")
        if (!productCard) {
          console.error("Product card not found")
          return
        }

        const productId = productCard.getAttribute("data-product-id")
        if (!productId) {
          console.error("Product ID not found")
          return
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')
        if (!csrfToken) {
          console.error("CSRF token not found")
          return
        }

        // Handle both icon types
        const heartIcon =
          this.querySelector(".wishlist-icon") ||
          this.querySelector(".ri-heart-line") ||
          this.querySelector(".ri-heart-fill")

        const isCurrentlyActive = this.classList.contains("active")

        // Toggle active state immediately for better UX
        this.classList.toggle("active")

        // Update icon immediately for better UX
        if (heartIcon) {
          if (isCurrentlyActive) {
            // If it was active and we're removing from wishlist
            heartIcon.classList.remove("ri-heart-fill", "text-red-500")
            heartIcon.classList.add("ri-heart-line")
          } else {
            // If it was not active and we're adding to wishlist
            heartIcon.classList.remove("ri-heart-line")
            heartIcon.classList.add("ri-heart-fill", "text-red-500")
          }
        }

        // Send request to server
        fetch("/wishlist/toggle", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
          },
          body: JSON.stringify({
            product_id: productId,
          }),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error(`Server responded with status: ${response.status}`)
            }
            return response.json()
          })
          .then((data) => {
            if (data.status === "success") {
              if (data.action === "added") {
                showToast("Product added to wishlist", "success")
              } else {
                showToast("Product removed from wishlist", "info")
              }

              updateWishlistCount()
            } else {
              // Revert visual state if there was an error
              this.classList.toggle("active")

              if (heartIcon) {
                if (isCurrentlyActive) {
                  heartIcon.classList.remove("ri-heart-line")
                  heartIcon.classList.add("ri-heart-fill", "text-red-500")
                } else {
                  heartIcon.classList.remove("ri-heart-fill", "text-red-500")
                  heartIcon.classList.add("ri-heart-line")
                }
              }

              showToast(data.message || "Error updating wishlist", "error")
            }
          })
          .catch((error) => {
            console.error("Error toggling wishlist item:", error)

            // Revert visual state if there was an error
            this.classList.toggle("active")

            if (heartIcon) {
              if (isCurrentlyActive) {
                heartIcon.classList.remove("ri-heart-line")
                heartIcon.classList.add("ri-heart-fill", "text-red-500")
              } else {
                heartIcon.classList.remove("ri-heart-fill", "text-red-500")
                heartIcon.classList.add("ri-heart-line")
              }
            }

            showToast("Error updating wishlist. Please try again.", "error")
          })
      })
    })
  }

  /**
   * Update wishlist count in the UI
   */
  function updateWishlistCount() {
    if (document.body.classList.contains("logged-in")) {
      fetch("/wishlist/count", {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Accept": "application/json"
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          if (data.status === "success") {
            const wishlistCounters = document.querySelectorAll(".wishlist-count")
            wishlistCounters.forEach((counter) => {
              counter.textContent = data.count

              if (data.count > 0) {
                counter.classList.remove("hidden")
              } else {
                counter.classList.add("hidden")
              }
            })

            // Update header wishlist count if the function exists
            if (typeof window.updateWishlistDisplay === "function") {
              window.updateWishlistDisplay(data.count)
            }
          }
        })
        .catch((error) => console.error("Error updating wishlist count:", error))
    }
  }

  /**
   * Initialize "Add to Cart" functionality
   */
  function initAddToCart() {
    // Use event delegation instead of attaching listeners to each button
    // Remove any existing event listeners first
    document.removeEventListener("click", handleAddToCartClick, true)
    
    // Add a single event listener for all add-to-cart buttons
    document.addEventListener("click", handleAddToCartClick, true)
    
    // Check which products are already in the cart and update their buttons
    checkProductsInCart()
  }
  
  /**
   * Handle add to cart button clicks
   */
  function handleAddToCartClick(e) {
    // Find the closest add-to-cart button to the clicked element
    const btn = e.target.closest(".add-to-cart-btn")
    
    // If we didn't click on or inside an add-to-cart button, do nothing
    if (!btn) return
    
    // Prevent default action and stop propagation
    e.preventDefault()
    e.stopPropagation()
    
    // Check if we're already processing this button
    if (btn.getAttribute("data-processing") === "true") {
      console.log("Already processing this button, ignoring click")
      return
    }
    
    // Mark this button as being processed
    btn.setAttribute("data-processing", "true")
    
    // Get product ID directly from button or from parent card
    let productId = btn.getAttribute("data-product-id")
    
    // If not found on button, try to get from parent card
    if (!productId) {
      const productCard = btn.closest(".product-card")
      if (productCard) {
        productId = productCard.getAttribute("data-product-id")
      }
    }
    
    if (!productId) {
      console.error("Product ID not found")
      showToast("Error: Product ID not found", "error")
      btn.setAttribute("data-processing", "false")
      return
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
    if (!csrfToken) {
      console.error("CSRF token not found")
      showToast("Error: CSRF token not found", "error")
      btn.setAttribute("data-processing", "false")
      return
    }
    
    // Store original button text
    const originalText = btn.innerHTML
    
    // Show loading state
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...'
    btn.disabled = true
    
    console.log("Adding product to cart:", productId)
    
    // Create form data for the request
    const formData = new FormData()
    formData.append("product_id", productId)
    formData.append("quantity", 1)
    
    // Use the correct endpoint for adding to cart
    fetch("/cart/ajax/add", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json"
      },
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        console.log("Add to cart response:", data)
        
        // Always restore the button to its original state
        btn.innerHTML = originalText
        btn.disabled = false
        
        if (data.success) {
          // Show success message
          showToast("Product added to cart", "success")
          
          // Update button to show cart icon instead of bag
          const bagIcon = btn.querySelector(".ri-shopping-bag-2-line, .ri-shopping-bag-line")
          if (bagIcon) {
            bagIcon.classList.remove("ri-shopping-bag-2-line", "ri-shopping-bag-line")
            bagIcon.classList.add("ri-checkbox-circle-line")
          }
          
          // Store in localStorage that this product is in cart
          markProductAsInCart(productId)
          
          // Update cart display if the function exists in the global scope
          if (data.cart && typeof window.updateCartDisplay === "function") {
            window.updateCartDisplay(data.cart)
          } else {
            // If cart count is not in the response, try to fetch it separately
            fetchCartCount()
          }
          
          // Open cart sidebar if the function exists
          if (typeof window.toggleCartSidebar === "function") {
            window.toggleCartSidebar()
          }
        } else {
          showToast(data.message || "Error adding product to cart", "error")
        }
      })
      .catch((error) => {
        console.error("Error adding product to cart:", error)
        showToast("Error adding product to cart. Please try again.", "error")
        
        // Restore original button state
        btn.innerHTML = originalText
        btn.disabled = false
      })
      .finally(() => {
        // Mark this button as no longer being processed
        btn.setAttribute("data-processing", "false")
      })
  }

  /**
   * Mark a product as being in the cart in localStorage
   */
  function markProductAsInCart(productId) {
    const productsInCart = JSON.parse(localStorage.getItem("productsInCart") || "[]")
    if (!productsInCart.includes(productId)) {
      productsInCart.push(productId)
      localStorage.setItem("productsInCart", JSON.stringify(productsInCart))
    }
  }

  /**
   * Check which products are in the cart and update their buttons
   */
  function checkProductsInCart() {
    // First, check localStorage for products we know are in cart
    let productsInCart = JSON.parse(localStorage.getItem("productsInCart") || "[]")

    // Then, fetch the current cart from the server to ensure accuracy
    fetch("/cart/ajax/get", {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        if (data.success && data.cart && data.cart.items) {
          // Update our local storage with accurate cart data
          productsInCart = data.cart.items.map((item) => item.product_id.toString())
          localStorage.setItem("productsInCart", JSON.stringify(productsInCart))

          // Update all add to cart buttons based on cart contents
          updateAddToCartButtons(productsInCart)

          // Update cart display in header if the function exists
          if (typeof window.updateCartDisplay === "function") {
            window.updateCartDisplay(data.cart)
          }
        }
      })
      .catch((error) => {
        console.error("Error fetching cart data:", error)

        // Still update buttons based on localStorage as a fallback
        updateAddToCartButtons(productsInCart)
      })
  }

  /**
   * Update all Add to Cart buttons based on which products are in the cart
   */
  function updateAddToCartButtons(productsInCart) {
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn")

    addToCartButtons.forEach((btn) => {
      // Get product ID from button or parent card
      let productId = btn.getAttribute("data-product-id")
      if (!productId) {
        const productCard = btn.closest(".product-card")
        if (productCard) {
          productId = productCard.getAttribute("data-product-id")
        }
      }

      if (productId && productsInCart.includes(productId.toString())) {
        // This product is in the cart, update button icon but not background
        const bagIcon = btn.querySelector(".ri-shopping-bag-2-line, .ri-shopping-bag-line")
        if (bagIcon) {
          bagIcon.classList.remove("ri-shopping-bag-2-line", "ri-shopping-bag-line")
          bagIcon.classList.add("ri-checkbox-circle-line")
        }
      }
    })
  }

  /**
   * Fetch cart count from server
   */
  function fetchCartCount() {
    // Only proceed if we have cart counters on the page
    if (document.querySelectorAll(".cart-count").length === 0) return

    fetch("/cart/ajax/get", {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        if (data.success && data.cart) {
          // Update cart count in UI
          updateCartCount(data.cart.items.length)

          // Update cart display in header if the function exists
          if (typeof window.updateCartDisplay === "function") {
            window.updateCartDisplay(data.cart)
          }

          // Update our local storage with accurate cart data
          const productsInCart = data.cart.items.map((item) => item.product_id.toString())
          localStorage.setItem("productsInCart", JSON.stringify(productsInCart))

          // Update all add to cart buttons based on cart contents
          updateAddToCartButtons(productsInCart)
        }
      })
      .catch((error) => {
        console.error("Error fetching cart data:", error)
      })
  }

  /**
   * Update cart count in UI
   */
  function updateCartCount(count) {
    const cartCounters = document.querySelectorAll(".cart-count")
    cartCounters.forEach((counter) => {
      counter.textContent = count

      if (count > 0) {
        counter.classList.remove("hidden")
      } else {
        counter.classList.add("hidden")
      }
    })
  }
})