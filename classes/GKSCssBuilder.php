<?php

class GKSCssBuilder {

    public function build($gks_slider, $isSlider)
    {
          ob_start();

          $options = $gks_slider->options;
          $isCarousel = (!empty($options[GKSOption::kViewType]) && $options[GKSOption::kViewType] == GKSViewType::SliderCarousel);
          $isCoverflow = (!empty($options[GKSOption::kViewType]) && $options[GKSOption::kViewType] == GKSViewType::SliderCoverflow);
          $margin = 0;
          $sliderPadding = !empty($options[GKSOption::kSliderPadding]) ? $options[GKSOption::kSliderPadding] : 0;;
          $padding = !empty($options[GKSOption::kTilePaddings]) ? $options[GKSOption::kTilePaddings] : 0;
          $textPadding = !empty($options[GKSOption::kSliderInfoPadding]) ? $options[GKSOption::kSliderInfoPadding] : ($isCarousel ? 0 : $padding);
          $arrowPadding = !empty($options[GKSOption::kSliderArrowsPadding]) ? $options[GKSOption::kSliderArrowsPadding] : $padding;
          $isScrollerAvailable = (!empty($options[GKSOption::kViewType]) && $options[GKSOption::kViewType] == GKSViewType::SliderScroller);

          $textButtonDisplayStyle = !empty($options[GKSOption::kTextButtonDisplayStyle]) ? $options[GKSOption::kTextButtonDisplayStyle] : GKSTextButtonDisplayStyle::Jelly;

          $scope = '#gks-slider-' . esc_attr($gks_slider->id);
          $scopeScroller = '#gks-scroller-' . esc_attr($gks_slider->id);

          // Appy general fonts
          if ($options[GKSOption::kFont] && !empty($options[GKSOption::kFont])) {
            echo $scope . " .gks-slider-cell *:not(i) { font-family: " . esc_attr($options[GKSOption::kFont]) . "; }";
            echo $scopeScroller . " *:not(i) { font-family: " . esc_attr($options[GKSOption::kFont]) . "; }";
            echo ".gks-lg-popup-" . esc_attr($gks_slider->id) . " *:not(.lg-icon) { font-family: " . esc_attr($options[GKSOption::kFont]) . "; }";
          }

          if ($options[GKSOption::kTileTitleFont] && !empty($options[GKSOption::kTileTitleFont])) {
            echo ".gks-lg-popup-" . esc_attr($gks_slider->id) . " .lg-sub-html h4 { font-family: " . esc_attr($options[GKSOption::kTileTitleFont]) . "; }";
          }

          echo "
    			$scope .owl-carousel {
    				padding-left: " . esc_attr($sliderPadding) . "px;
    				padding-right: " . esc_attr($sliderPadding) . "px;
    			}";

          echo "
          @media only screen and (max-width: 600px) {
            $scope .owl-carousel {
      				padding-left: 0px;
      				padding-right: 0px;
      			}
          }
          ";

          $hasShadow = false;

          if ($isCarousel) {
            $cellContent = '';
            $cellHover = '';
            $overlayRadius = '';
            $borderWidth = $options[GKSOption::kBorderWidth];
            $contentBox = '';
            $contentBoxHover = '';
            $borderBox = '';
            $borderBoxHover = '';
            $imgWrapper = '';
            $imgWrapperHover = '';

            if (!empty($options[GKSOption::kShowBorder]) && $options[GKSOption::kShowBorder]) {
                $borderC = !empty($options[GKSOption::kBorderColor]) ? $options[GKSOption::kBorderColor] : '#000000';
                $borderBox .= "border: " . esc_attr($borderWidth) . "px solid " . esc_attr($borderC) . ";";
                $borderBox .= "opacity: 1;";
            }

            if (!empty($options[GKSOption::kShowBorderOnHover]) && $options[GKSOption::kShowBorderOnHover]) {
                $borderC = !empty($options[GKSOption::kBorderColor]) ? $options[GKSOption::kBorderColor] : '#000000';
                $borderBoxHover .= "border: " . esc_attr($borderWidth) . "px solid " . esc_attr($borderC) . ";";
                $borderBoxHover .= "opacity: 1;";
            }

            if (!empty($options[GKSOption::kShowShadow]) && $options[GKSOption::kShowShadow] && $options[GKSOption::kSpaceBtwSlides] != 0) {
                $shc = !empty($options[GKSOption::kShadowColor]) ? $options[GKSOption::kShadowColor] : '#000000';
                $contentBox .= "box-shadow: 0 0 20px " . esc_attr($shc) . ";";
                $hasShadow = true;
            }

            if (!empty($options[GKSOption::kSlideBgColor])) {
                $contentBox .= "background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kSlideBgColor].$options[GKSOption::kSlideBgOpacity])).";";
            }

            if (!empty($options[GKSOption::kBorderRadius])) {
                $contentBox .= "border-radius: " . esc_attr($options[GKSOption::kBorderRadius]) . "px;";

                if (!empty($borderBox)) {
                  $borderBox .= "border-radius: " . esc_attr($options[GKSOption::kBorderRadius]) . "px;";
                } else if(!empty($borderBoxHover)) {
                  $borderBoxHover .= "border-radius: " . esc_attr($options[GKSOption::kBorderRadius]) . "px;";
                }
            }
            if (!empty($options[GKSOption::kSlideBgHoverColor])) {
                $contentBoxHover .= "background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kSlideBgHoverColor].$options[GKSOption::kSlideBgHoverOpacity])).";";
            }

            if (!empty($options[GKSOption::kShowShadowOnHover]) && $options[GKSOption::kShowShadowOnHover]  && $options[GKSOption::kSpaceBtwSlides] != 0) {
                $shc = !empty($options[GKSOption::kShadowColor]) ? $options[GKSOption::kShadowColor] : '#000000';
                $contentBoxHover .= "box-shadow: 0 0 20px " . esc_attr($shc) . ";";
                $hasShadow = true;
            }
            $contentBox .= "padding: " . esc_attr($options[GKSOption::kSlidePadding]) . "px;";

            $sliderHeight = $options[GKSOption::kSliderHeight];
            $imgHeight = "{$sliderHeight}px";
            if (!empty($options[GKSOption::kInfoDisplayStyle])) {
                $titleCss = '';
                if ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnBottom) {
                    $titleCss = 'bottom: 0;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnTop) {
                    $titleCss = 'top: 0;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnBottomHover) {
                    $titleCss = 'bottom: 0;opacity:0;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnTopHover) {
                    $titleCss = 'top: 0;opacity:0;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnCenter) {
                    $titleCss = 'transform: translateY(-50%);top: 50%;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::OnCenterHover) {
                    $titleCss = 'transform: translateY(-50%);top: 50%;opacity:0;';
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::Before) {
                } elseif ($options[GKSOption::kInfoDisplayStyle] == GKSInfoDisplayStyle::After) {
                }
                $titleCss .= "padding: " . esc_attr($textPadding) . "px;";

                echo "$scope .gks-slider-onslide-details { {$titleCss} }"; //Already escaped

                echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slider-onslide-details {
                    opacity: 1;
                }";

                if ($options[GKSOption::kTileTitleFont] && !empty($options[GKSOption::kTileTitleFont])) {
                    echo "$scope .gks-slider-title { font-family: " . esc_attr($options[GKSOption::kTileTitleFont]) . " !important; }";
                }

                echo "$scope .gks-slider-cell-content-image {
                        height: " . esc_attr($imgHeight) . ";
                      }";
            }

            echo "$scope .owl-carousel .gks-slider-cell-content { $contentBox }";
            if (!empty($contentBoxHover)) {
                echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slider-cell-content { $contentBoxHover }"; //Already escaped
            }

            echo "$scope .owl-carousel .gks-slide-border { $borderBox }"; //Already escaped
            if (!empty($borderBoxHover)) {
                echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slide-border { $borderBoxHover }"; //Already escaped
            }


            echo "$scope .owl-carousel .gks-slider-cell-content {
                    $cellContent
                  }"; //Already escaped
            if (!empty($cellHover)) {
                echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slider-cell-content{
                        $cellHover
                      }"; //Already escaped
            }

           echo "
           $scope .owl-carousel .gks-slider-cell:hover .gks-slider-overlay {
  	          background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kTileOverlayColor].$options[GKSOption::kTileOverlayOpacity]))." !important;
  	          opacity: 1;
	            {$overlayRadius}
           }"; //Already escaped

          if ($hasShadow) {
              echo "$scope .owl-carousel .owl-item {padding: 25px;}";
          }
          if (!empty($imgWrapper)) {
              echo "$scope .owl-carousel .gks-slider-image-wrapper { $imgWrapper }"; //Already escaped
          }
          if (!empty($imgWrapperHover)) {
              echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slider-image-wrapper { $imgWrapperHover }"; //Already escaped
          }

          if ($options[GKSOption::kAppearButtonOnHover]) {
              echo "$scope .owl-carousel .gks-slider-cell .gks-slider-button { opacity:0; }";
              echo "$scope .owl-carousel .gks-slider-cell:hover .gks-slider-button { opacity:1; }";
          }
          if ($options[GKSOption::kButtonStyle] == GKSButtonStyle::Circle) {
              echo "$scope .owl-carousel .gks-slider-cell .gks-slider-button-icon { border-radius: 20px; }";
          }
          echo "$scope .owl-carousel .gks-slider-cell .gks-slider-button-icon {
                  color: " . esc_attr($options[GKSOption::kButtonIconColor]) . ";
                  background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kButtonBgColor].$options[GKSOption::kButtonBgOpacity])) .";
              }
              $scope .owl-carousel .gks-slider-cell .gks-slider-button-icon:hover {
                  background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kButtonBgHoverColor].$options[GKSOption::kButtonBgHoverOpacity])) .";
              }
              ";

          $textButtonTextColor = !empty($options[GKSOption::kTextButtonTextColor]) ? $options[GKSOption::kTextButtonTextColor] : '#fff';
          $textButtonBgColor = !empty($options[GKSOption::kTextButtonBgColor]) ? $options[GKSOption::kTextButtonBgColor] : '#000';

          echo "$scope .owl-carousel .gks-slider-cell .gks-slide-txt-btn {
                  color: " . esc_attr($textButtonTextColor) . ";
                  border-color: " . esc_attr($textButtonBgColor) . ";
                  text-decoration: none;
                  margin-top: 10px;
              }";


          if ($textButtonDisplayStyle == GKSTextButtonDisplayStyle::Jelly ||
              $textButtonDisplayStyle == GKSTextButtonDisplayStyle::MaterialCircle ||
              $textButtonDisplayStyle == GKSTextButtonDisplayStyle::MaterialFlat ||
              $textButtonDisplayStyle == GKSTextButtonDisplayStyle::Pill) {

            echo "$scope .owl-carousel .gks-slider-cell .gks-slide-txt-btn {
                    background-color: " . esc_attr($textButtonBgColor) . ";
              }";
          } else if($textButtonDisplayStyle == GKSTextButtonDisplayStyle::Slant) {

            echo "$scope .owl-carousel .gks-slider-cell .gks-slide-txt-btn:after {
                    background-color: " . esc_attr($textButtonTextColor) . ";
              }";

            echo "$scope .owl-carousel .gks-slider-cell .gks-slide-txt-btn:before {
                    background-color: " . esc_attr($textButtonBgColor) . ";
                    background-opacity: 0.5;
                    box-shadow: none !important;
              }";
          }
        } else {
          echo "
            $scope.gks-slider-mobile .gks-slider-title {
                height: ". esc_attr($options[GKSOption::kTileTitleFontSize] + 2)."px;
                overflow-y: hidden;
            }

            $scope.gks-slider-mobile .gks-slider-desc {
                height: " . esc_attr(isset($options[GKSOption::kFontSize]) ? $options[GKSOption::kFontSize] + 2 : 40) . "px;
                overflow-y: hidden;
            }

            $scope.gks-slider-mobile .gks-slider-title + .gks-slider-desc {
                height: ". esc_attr(isset($options[GKSOption::kFontSize]) ? $options[GKSOption::kFontSize] + 12 : 40) ."px;
                overflow-y: hidden;
            }
        ";
        }



        if (!$options[GKSOption::kShowPaginationMobile]) {
            echo "
                $scope.gks-slider-mobile .owl-dots {
                    display: none;
                }
            ";
        }

        if (!$options[GKSOption::kShowInfoMobile]) {
            echo "
                $scope.gks-slider-mobile .gks-slider-overlay-caption {
                    display: none;
                }
            ";
        }

        if (!$options[GKSOption::kSliderAutoHeight]) {
            echo "
                $scope .gks-slider-image-wrapper {
                    height: " . esc_attr($options[GKSOption::kSliderHeight]) . "px;
                }
            ";
        }

			echo "
      $scope .gks-slider-image,
      $scope .gks-tile-img {
        background-size: " . esc_attr($options[GKSOption::kImageBackgroundSize]) . ";
        background-position: " . esc_attr($options[GKSOption::kImageBackgroundPosition]) . ";
      }

      $scope .gks-slider-overlay-caption {
          background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kTileOverlayColor].$options[GKSOption::kTileOverlayOpacity]))." !important;
      }

      ";

      $showPagination = (!empty($options[GKSOption::kShowPagination]) && $options[GKSOption::kShowPagination]);
      $arrowPos = "

      $scope .gks-slider-ctrl-prev,
	    $scope .gks-slider-ctrl-next {";
        if (empty($options[GKSOption::kArrowPosition]) || $options[GKSOption::kArrowPosition] == GKSSliderArrowPosition::Center) {
            $topStr = '';
            if (!empty($options[GKSOption::kSliderPaginationPosition])) {
                if ($showPagination && $options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::BeforeSlider) {
                    $topStr = 'calc(50% + 20px)';
                } elseif ($showPagination && $options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::AfterSlider) {
                    $topStr = 'calc(50% - 15px)';
                } else {
                    $topStr = '50%';
                }
            }
            $arrowPos .= 'top: '.$topStr.'; transform: translateY(calc(-50% - 7px));'; //Hardcoded css calc
        } elseif($options[GKSOption::kArrowPosition] == GKSSliderArrowPosition::Bottom) {
            $topStr = '';
            if (!empty($options[GKSOption::kSliderPaginationPosition])) {
                if ($showPagination && $options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::AfterSlider) {
                    $topStr = ($hasShadow ? '55px' : '41px');
                } else {
                    $topStr = ($hasShadow ? '25px' : '0');
                }
            }
            $arrowPos .= 'bottom: '.$topStr.'; transform: translateY(0);'; //Hardcoded value
        } else {
            $topStr = '';
            if (!empty($options[GKSOption::kSliderPaginationPosition])) {
                if ($showPagination && $options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::BeforeSlider) {
                    $topStr = ($hasShadow ? '65px' : '40px');
                } else {
                    $topStr = ($hasShadow ? '25px' : '0');
                }
            }
            $arrowPos .= 'top: '.$topStr.'; transform: translateY(0);'; //Hardcoded value
        }
        $arrowPos .= "
            }
        ";
        echo $arrowPos;

        $appearArrowsOnHover = (!empty($options[GKSOption::kAppearArrowsOnHover]) && $options[GKSOption::kAppearArrowsOnHover]);
        if ($appearArrowsOnHover) {
          echo "
              $scope .gks-slider-ctrl-prev,
    	        $scope .gks-slider-ctrl-next {
                opacity: 0;
              }

              $scope.gks-slider-layout:hover .gks-slider-ctrl-prev,
    	        $scope.gks-slider-layout:hover .gks-slider-ctrl-next {
                opacity: 1;
              }
          ";
        }
        if ($options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Dots || $options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Squares) {
            echo "
            $scope .owl-dots .owl-dot span {
                margin: 5px 5px;
                width: 12px;
                height: 12px;
            }
            $scope .owl-dots .owl-dots {
                line-height: 12px;
            }
		";
        } elseif ($options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Ovals || $options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Rectangles) {
            echo "
             $scope .owl-dots {
                line-height: 10px;
            }
            $scope .owl-dots .owl-dot span {
                margin: 5px 5px;
                width: 30px;
                height: 10px;
            }

		";
        }

        if ($options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Dots || $options[GKSOption::kSliderPaginationStyle] == GKSSliderPaginationStyle::Ovals) {
            echo "
             $scope .owl-dots .owl-dot span {
                -webkit-border-radius: 30px;
                -moz-border-radius: 30px;
                border-radius: 30px;
            }
            ";
        }

        $captionCss = "$scope.gks-slider-layout .gks-slider-overlay-caption {";
        $mobileCaptionCss = " $scope.gks-slider-mobile .gks-slider-overlay-caption {";
        $notMobileCaptionCss = " $scope:not(.gks-slider-mobile) .gks-slider-overlay-caption {";

        if ($options[GKSOption::kSliderTextWidthStyle] == GKSSliderTextWidthStyle::Fill) {
            $captionCss .= "
                width: 100%;
            ";
            if ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::BottomCenter ||
                $options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::RightBottom ||
                $options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::LeftBottom) {
                $captionCss .= "
                    bottom: 0;
                ";
                if ($options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::OnSliderBottom) {
                    $captionCss .= "
                        padding-bottom: 35px;
                    ";
                }
            } else {
                $captionCss .= "
                    top: 0;
                ";
                if ($options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::OnSliderTop) {
                    $captionCss .= "
                        padding-top: 35px;
                    ";
                }
            }
        } else {
            if ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::BottomCenter) {
                $captionCss .= "
                    left: 50%;
                    bottom: " . esc_attr($textPadding) . "px;
                    transform: translateX(-50%);
                ";
                $mobileCaptionCss .= "
                    width: calc(100% - ". esc_attr(2*$textPadding) ."px);
                ";
                $notMobileCaptionCss .= "
                    min-width: 60%;
                ";
            } elseif ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::TopCenter) {
                $captionCss .= "
                    left: 50%;
                    top: " . esc_attr($textPadding) . "px;
                    transform: translateX(-50%);
                ";
                $mobileCaptionCss .= "
                    width: calc(100% - ". esc_attr(2*$textPadding) ."px);
                ";
                $notMobileCaptionCss .= "
                    min-width: 60%;
                ";
            } elseif ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::LeftBottom) {
                $captionCss .= "
                    left: " . esc_attr($textPadding) . "px;
                    bottom: " . esc_attr($textPadding) . "px;
                    margin-right: " . esc_attr($textPadding) . "px;
                ";
                $notMobileCaptionCss .= "
                    max-width: 60%;
                ";
            } elseif ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::RightBottom) {
                $captionCss .= "
                    right: " . esc_attr($textPadding) . "px;
                    bottom: " . esc_attr($textPadding) . "px;
                    margin-left: " . esc_attr($textPadding) . "px;
                ";
                $notMobileCaptionCss .= "
                    max-width: 60%;
                ";
            } elseif ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::TopLeft) {
                $captionCss .= "
                    left: " . esc_attr($textPadding) . "px;
                    top: " . esc_attr($textPadding) . "px;
                    margin-right: " . esc_attr($textPadding) . "px;
                ";
                $notMobileCaptionCss .= "
                    max-width: 60%;
                ";
            } elseif ($options[GKSOption::kSliderTextPosition] == GKSSliderTextPosition::TopRight) {
                $captionCss .= "
                    right: " . esc_attr($textPadding) . "px;
                    top: " . esc_attr($textPadding) . "px;
                    margin-left: " . esc_attr($textPadding) . "px;
                ";
                $notMobileCaptionCss .= "
                    max-width: 60%;
                ";
            }
        }

        $captionCss .= "
                background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kTileOverlayColor].$options[GKSOption::kTileOverlayOpacity])).";
            }";


        $captionCss .= "
            $scope .gks-slider-title {
                color: ". esc_attr($options[GKSOption::kTileTitleColor]).";
                font-size: ". esc_attr($options[GKSOption::kTileTitleFontSize])."px;
                line-height: ". esc_attr($options[GKSOption::kTileTitleFontSize] + 2)."px;
                text-align: ". esc_attr($options[GKSOption::kTileTitleAlignment]).";
                margin-top: 5px;
                margin-bottom: 5px;
        ";

        if ($options[GKSOption::kTileTitleFont] && !empty($options[GKSOption::kTileTitleFont])) {
            $captionCss .= "
                font-family: " . esc_attr($options[GKSOption::kTileTitleFont]) . " !important;
            ";
        }

        if ($options[GKSOption::kTileTitleFontStyle] == 'italic') {
            $captionCss .= "
                font-style: " . esc_attr($options[GKSOption::kTileTitleFontStyle]) . ";
                font-weight: normal;
            ";
        } else {
            $captionCss .= "
                font-weight: " . esc_attr($options[GKSOption::kTileTitleFontStyle]) . ";
            ";
        }
        $captionCss .= "
            }
            $scope .gks-slider-desc,
            $scope .gks-slider-details {
                color: ". esc_attr($options[GKSOption::kTileDescColor]) .";
                font-size: ". esc_attr($options[GKSOption::kFontSize]) ."px;
                line-height: ". esc_attr(isset($options[GKSOption::kFontSize]) ? $options[GKSOption::kFontSize] + 2 : 13)."px;
                text-align: ". esc_attr($options[GKSOption::kTileDescAlignment]) .";
        ";
        if ($options[GKSOption::kFontStyle] == 'italic') {
            $captionCss .= "
            font-style: " . esc_attr($options[GKSOption::kFontStyle]) . ";
            font-weight: normal;
        ";
        } else {
            $captionCss .= "
            font-weight: " . esc_attr($options[GKSOption::kFontStyle]) . ";
        ";
        }
        $captionCss .= "
            }
        ";

        if ($isCarousel) {
          $textButtonFontSize = $options[GKSOption::kTextButtonFontSize] ? $options[GKSOption::kTextButtonFontSize] : 15;
          $captionCss .= "
              $scope .gks-slide-txt-btn {
                  font-size: ". esc_attr($textButtonFontSize) ."px !important;
                  line-height: ". esc_attr($textButtonFontSize + 2) ."px !important;
                  align-self: ". esc_attr($options[GKSOption::kTextButtonAlignment]).";
          ";
          if ($options[GKSOption::kTextButtonFontStyle] == 'italic') {
              $captionCss .= "
              font-style: " . esc_attr($options[GKSOption::kTextButtonFontStyle]) . ";
              font-weight: normal;
          ";
          } else {
              $captionCss .= "
              font-weight: " . esc_attr($options[GKSOption::kTextButtonFontStyle]) . ";
          ";
          }
        }


        $captionCss .= "}";


        $mobileCaptionCss .= "}";
        $notMobileCaptionCss .= "}";

        echo $captionCss;
        echo $mobileCaptionCss;
        echo $notMobileCaptionCss;

        echo "$scope .gks-slider-overlay-caption.gks-info-opened {
                max-height: calc(100% - ". esc_attr($textPadding * 2)."px);
          }";

        $arrowBgStyle = $options[GKSOption::kArrowBgStyle];
        $bgp = round($options[GKSOption::kArrowFontSize]/1.75);
        echo "
            $scope .gks-slider-ctrl-prev,
            $scope .gks-slider-ctrl-next {
        ";
        if ($arrowBgStyle != GKSSliderArrowBgStyle::None) {
            echo "background-color: ". esc_attr(GKSHelper::hex2rgba($options[GKSOption::kArrowBgColor].$options[GKSOption::kArrowBgOpacity])) .";";
        }
        if ($arrowBgStyle == GKSSliderArrowBgStyle::Dot) {
            echo "border-radius: ".esc_attr($bgp*2)."px;";
        }

        if (empty($margin)) {
            echo "
                margin-left: " . esc_attr($padding) . "px;
                margin-right: " . esc_attr($padding) . "px;
            ";
        }

        echo "
                padding: ". esc_attr($arrowPadding) ."px;
            }
            $scope .gks-slider-ctrl-prev .gks-fa,
            $scope .gks-slider-ctrl-next .gks-fa {
                color: ". esc_attr($options[GKSOption::kArrowColor]) .";
                font-size: ". esc_attr($options[GKSOption::kArrowFontSize]) ."px;
                width: ". esc_attr($options[GKSOption::kArrowFontSize]) ."px;
                height: ". esc_attr($options[GKSOption::kArrowFontSize]) ."px;
            }
        ";
        if ($arrowBgStyle != GKSSliderArrowBgStyle::None) {
            echo "
                $scope .gks-slider-ctrl-prev:hover,
                $scope .gks-slider-ctrl-next:hover,
                $scope .gks-slider-ctrl-prev:active,
                $scope .gks-slider-ctrl-next:active {
                    background-color: " . esc_attr(GKSHelper::hex2rgba($options[GKSOption::kArrowBgHoverColor] . $options[GKSOption::kArrowBgHoverOpacity])) . ";
                }
            ";
        }
        echo "
            $scope .gks-slider-ctrl-prev:hover .gks-fa,
            $scope .gks-slider-ctrl-next:hover .gks-fa,
            $scope .gks-slider-ctrl-prev:active .gks-fa,
            $scope .gks-slider-ctrl-next:active .gks-fa {
                color: ". esc_attr($options[GKSOption::kArrowHoverColor]).";
            }

            $scope.gks-slider-layout .owl-dots .owl-dot span,
            $scope.gks-slider-layout .owl-dots .owl-dot span {
                background: ". esc_attr($options[GKSOption::kPaginationColor]).";
            }

            $scope.gks-slider-layout .owl-dots .owl-dot.active span,
            $scope.gks-slider-layout .owl-dots .owl-dot:hover span {
                background: ". esc_attr($options[GKSOption::kPaginationHoverColor]).";
            }

        ";

        if ($options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::OnSliderBottom) {
            echo "
            $scope.gks-slider-layout .owl-dots {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            ";
        } else if ($options[GKSOption::kSliderPaginationPosition] == GKSSliderPaginationPosition::OnSliderTop) {
            echo "
            $scope.gks-slider-layout .owl-dots {
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            ";
        } else {
            echo "
            $scope.gks-slider-layout .owl-dots {
               padding-top: 10px;
            }
            ";
        }

        if ($isCarousel) {
            $infoDisplyStyle = !empty($gks_slider->options[GKSOption::kInfoDisplayStyle]) ? $gks_slider->options[GKSOption::kInfoDisplayStyle] : GKSInfoDisplayStyle::OnBottom;
            if ($infoDisplyStyle == GKSInfoDisplayStyle::After) {
              echo "
                .gks-carousel	.gks-slider-slide-caption {
                  justify-content: flex-start; /* flex-start | flex-end | center */
                }
              ";
            } else if($infoDisplyStyle == GKSInfoDisplayStyle::Before) {
              echo "
                .gks-carousel	.gks-slider-slide-caption {
                  justify-content: flex-end; /* flex-start | flex-end | center */
                }
              ";
            }
        }

        //Scroller styles
        if ($isScrollerAvailable) {
          echo "
          $scopeScroller.gks-scroller-layout {
            position: relative;
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left:  ". esc_attr($sliderPadding) ."px;
            padding-right:  ". esc_attr($sliderPadding) ."px;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-ctrl {
            position: absolute;
            top: calc(50%);
            transform: translateY(-50%);
            height: 100%;
            width: 30px;
            z-index: 2;
            background-color: rgba(0,0,0,0.5);
            text-align: center;
            cursor: pointer;
            -webkit-transition: opacity 0.3s ease-in-out;
            -moz-transition: opacity 0.3s ease-in-out;
            transition: opacity 0.3s ease-in-out;

            opacity: 0;
          }

          $scopeScroller.gks-scroller-layout:hover .gks-scroller-ctrl{
            opacity: 1;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-ctrl-prev{
            left: ". esc_attr($sliderPadding) ."px;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-ctrl-next{
            right: ". esc_attr($sliderPadding) ."px;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-ctrl .gks-fa {
            position: absolute;
            left: 0;
            top: 50%;
            display: table-cell;
            vertical-align: middle;
            transform: translateY(-50%);
            color: white;
            font-size: 20px;
            width: 100%;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell-content-image {
            width: 100%;
            height: ". esc_attr($options[GKSOption::kScrollerHeight]) ."px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            -webkit-transition: opacity 0.3s ease-in-out;
            -moz-transition: opacity 0.3s ease-in-out;
            transition: opacity 0.3s ease-in-out;

            opacity: 0.6;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell-content-border,
          $scopeScroller.gks-scroller-layout .gks-scroller-cell-content-border-default {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 4px solid ". esc_attr($options[GKSOption::kScrollerActiveThumbBorderColor]) .";
            -webkit-transition: opacity 0.3s ease-in-out;
            -moz-transition: opacity 0.3s ease-in-out;
            transition: opacity 0.3s ease-in-out;

            opacity: 0;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell-content-border-default {
            border: 4px solid ". esc_attr($options[GKSOption::kScrollerThumbBorderColor]) .";
            opacity: 1;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-label {
              display: inline-block;
              width: 100%;
              text-align: center;
              position: absolute;
              top: 50%;
              transform: translateY(-50%);
              color: ". esc_attr($options[GKSOption::kScrollerThumbTitleColor]) .";
              font-weight: bold;
              text-overflow: ellipsis;
              white-space: nowrap;
              overflow: hidden;
              padding: 20px;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell {
            cursor: pointer;
            background: ". esc_attr($options[GKSOption::kScrollerThumbBgColor]) .";
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell.active {
            cursor: pointer;
            background: ". esc_attr($options[GKSOption::kScrollerActiveThumbBgColor]) .";
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell.active .gks-scroller-cell-content-border {
            opacity: 1;
          }

          $scopeScroller.gks-scroller-layout .gks-scroller-cell.active .gks-scroller-cell-content-image,
          $scopeScroller.gks-scroller-layout .gks-scroller-cell:hover .gks-scroller-cell-content-image {
            opacity: 1;
          }
          ";
        }
        ?>

        <?php if (!empty($gks_slider->options[GKSOption::kViewerType]) && $gks_slider->options[GKSOption::kViewerType] == GKSPjViewerType::LightGalleryFixed) { ?>

        .fixed-size-frame.fixed-size-frame-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-sub-html {
          position: absolute;
          text-align: left;
        <?php if (isset($gks_slider->options[GKSOption::kPopupTitlePosition]) && $gks_slider->options[GKSOption::kPopupTitlePosition] == GKSPopupTitlePosition::OnTop) { ?>
            top: 0;
            bottom: auto;
        <?php } else { ?>
            top: auto;
            bottom: 0;
        <?php } ?>
        }

        .fixed-size-frame.fixed-size-frame-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-toolbar {
          background-color: transparent;
          height: 0;
        }

        .fixed-size-frame.fixed-size-frame-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-inner {
          padding: 4px 0;
          /*background-color: rgba(0,0,0,0.85);*/
        }

        <?php } ?>

        <?php if (isset($gks_slider->options[GKSOption::kViewerType]) && $gks_slider->options[GKSOption::kViewerType] == GKSPjViewerType::LightGalleryLight && isset($gks_slider->options[GKSOption::kPopupTitlePosition]) && $gks_slider->options[GKSOption::kPopupTitlePosition] == GKSPopupTitlePosition::OnBottom) { ?>
        .fixed-size.fixed-size-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-sub-html {
          top: auto !important;
          bottom: 0;
          background: transparent !important;
          width: 100%;
        }
        .fixed-size.fixed-size-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-inner .lg-img-wrap {
          padding: 10px 5px 40px 5px;
        }
      <?php } else { ?>
        .fixed-size.fixed-size-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-sub-html {
          top: 0;
          bottom: auto!important;
          background: transparent!important;
          width: 50%;
        }
        .fixed-size.fixed-size-<?php echo esc_attr($gks_slider->id); ?>.lg-outer .lg-inner .lg-img-wrap {
          padding: 40px 5px 10px 5px;
        }
      <?php } ?>



      <?php if (isset($gks_slider->options[GKSOption::kPopupTheme]) && $gks_slider->options[GKSOption::kPopupTheme] == GKSPopupTheme::Light) { ?>
        .lg-backdrop.gks-popup-backdrop-<?php echo esc_attr($gks_slider->id); ?> {
          background-color: #ffffff;
        }
      <?php } ?>


      <?php

      $css = ob_get_clean();
  	  return $css;
  }
}
